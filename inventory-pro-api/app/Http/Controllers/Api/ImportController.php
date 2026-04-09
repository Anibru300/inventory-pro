<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockLevel;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Csv\Reader;
use League\Csv\Writer;

class ImportController extends Controller
{
    /**
     * Importar productos desde CSV
     */
    public function importProducts(Request $request)
    {
        $user = auth()->user();
        
        if (!$user || !$user->tenant_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240', // Max 10MB
            'warehouse_id' => 'nullable|exists:warehouses,id',
        ]);
        
        try {
            $file = $request->file('file');
            $path = $file->store('imports', 'local');
            $fullPath = storage_path('app/' . $path);
            
            // Leer CSV
            $csv = Reader::createFromPath($fullPath, 'r');
            $csv->setHeaderOffset(0);
            $csv->setDelimiter(',');
            
            $records = $csv->getRecords();
            $results = [
                'success' => [],
                'errors' => [],
                'total' => 0,
                'imported' => 0,
                'failed' => 0,
            ];
            
            // Obtener almacén principal si no se especificó
            $warehouseId = $request->input('warehouse_id');
            if (!$warehouseId) {
                $warehouse = Warehouse::where('tenant_id', $user->tenant_id)
                    ->where('is_primary', true)
                    ->first();
                $warehouseId = $warehouse?->id;
            }
            
            // Procesar en batches de 100
            $batch = [];
            foreach ($records as $index => $record) {
                $results['total']++;
                $rowNumber = $index + 2; // +2 porque empezamos en 1 y hay header
                
                $validation = $this->validateProductRow($record, $rowNumber, $user->tenant_id);
                
                if ($validation['valid']) {
                    $batch[] = [
                        'record' => $record,
                        'row' => $rowNumber,
                    ];
                    
                    // Procesar batch cada 100 registros
                    if (count($batch) >= 100) {
                        $this->processBatch($batch, $user->tenant_id, $warehouseId, $results);
                        $batch = [];
                    }
                } else {
                    $results['errors'][] = $validation['error'];
                    $results['failed']++;
                }
            }
            
            // Procesar registros restantes
            if (!empty($batch)) {
                $this->processBatch($batch, $user->tenant_id, $warehouseId, $results);
            }
            
            // Limpiar archivo temporal
            Storage::delete($path);
            
            Log::info('Importación de productos completada', [
                'tenant_id' => $user->tenant_id,
                'total' => $results['total'],
                'imported' => $results['imported'],
                'failed' => $results['failed'],
            ]);
            
            return response()->json([
                'message' => 'Importación completada',
                'results' => $results,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en importación de productos', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'message' => 'Error al importar: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Validar una fila del CSV
     */
    private function validateProductRow(array $row, int $rowNumber, string $tenantId): array
    {
        // Mapear columnas (soportar diferentes nombres)
        $name = $row['nombre'] ?? $row['name'] ?? null;
        $sku = $row['sku'] ?? $row['codigo'] ?? $row['code'] ?? null;
        $price = $row['precio'] ?? $row['price'] ?? null;
        $cost = $row['costo'] ?? $row['cost'] ?? null;
        
        if (empty($name)) {
            return [
                'valid' => false,
                'error' => "Fila {$rowNumber}: El nombre es obligatorio",
            ];
        }
        
        if (empty($sku)) {
            return [
                'valid' => false,
                'error' => "Fila {$rowNumber}: El SKU es obligatorio",
            ];
        }
        
        // Validar que el SKU no exista
        $exists = Product::where('tenant_id', $tenantId)
            ->where('sku', $sku)
            ->whereNull('deleted_at')
            ->exists();
        
        if ($exists) {
            return [
                'valid' => false,
                'error' => "Fila {$rowNumber}: El SKU '{$sku}' ya existe",
            ];
        }
        
        return ['valid' => true];
    }
    
    /**
     * Procesar un batch de productos
     */
    private function processBatch(array $batch, string $tenantId, ?string $warehouseId, array &$results): void
    {
        DB::beginTransaction();
        
        try {
            foreach ($batch as $item) {
                $record = $item['record'];
                $rowNumber = $item['row'];
                
                try {
                    // Mapear datos
                    $productData = [
                        'tenant_id' => $tenantId,
                        'name' => $record['nombre'] ?? $record['name'] ?? '',
                        'sku' => $record['sku'] ?? $record['codigo'] ?? $record['code'] ?? '',
                        'barcode' => $record['barcode'] ?? $record['codigo_barras'] ?? null,
                        'description' => $record['descripcion'] ?? $record['description'] ?? null,
                        'unit' => $record['unidad'] ?? $record['unit'] ?? 'pieza',
                        'price' => $this->parseNumber($record['precio'] ?? $record['price'] ?? 0),
                        'unit_cost' => $this->parseNumber($record['costo'] ?? $record['cost'] ?? 0),
                        'stock_min' => $this->parseNumber($record['stock_minimo'] ?? $record['min_stock'] ?? 0),
                        'stock_max' => $this->parseNumber($record['stock_maximo'] ?? $record['max_stock'] ?? 0),
                        'is_active' => true,
                        'valuation_method' => 'AVERAGE',
                    ];
                    
                    // Buscar o crear categoría
                    $categoryName = $record['categoria'] ?? $record['category'] ?? null;
                    if ($categoryName) {
                        $category = Category::firstOrCreate(
                            ['tenant_id' => $tenantId, 'name' => $categoryName],
                            ['slug' => Str::slug($categoryName), 'is_active' => true]
                        );
                        $productData['category_id'] = $category->id;
                    }
                    
                    // Crear producto
                    $product = Product::create($productData);
                    
                    // Crear stock inicial si se proporcionó
                    $initialStock = $this->parseNumber($record['stock_inicial'] ?? $record['initial_stock'] ?? 0);
                    if ($initialStock > 0 && $warehouseId) {
                        StockLevel::create([
                            'tenant_id' => $tenantId,
                            'product_id' => $product->id,
                            'warehouse_id' => $warehouseId,
                            'quantity' => $initialStock,
                            'avg_unit_cost' => $productData['unit_cost'],
                        ]);
                    }
                    
                    $results['success'][] = "Fila {$rowNumber}: {$productData['name']} importado";
                    $results['imported']++;
                    
                } catch (\Exception $e) {
                    $results['errors'][] = "Fila {$rowNumber}: " . $e->getMessage();
                    $results['failed']++;
                }
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    /**
     * Parsear número (soportar coma y punto como separador decimal)
     */
    private function parseNumber($value): float
    {
        if (empty($value)) return 0;
        
        // Reemplazar coma por punto
        $value = str_replace(',', '.', $value);
        
        return floatval($value);
    }
    
    /**
     * Descargar template de importación
     */
    public function downloadTemplate()
    {
        $headers = [
            'nombre',
            'sku',
            'codigo_barras',
            'descripcion',
            'categoria',
            'unidad',
            'precio',
            'costo',
            'stock_minimo',
            'stock_maximo',
            'stock_inicial',
        ];
        
        // Datos de ejemplo
        $example = [
            'Producto Ejemplo',
            'SKU001',
            '7501234567890',
            'Descripción del producto',
            'Categoría General',
            'pieza',
            '199.99',
            '100.00',
            '10',
            '100',
            '50',
        ];
        
        $csv = Writer::createFromString('');
        $csv->insertOne($headers);
        $csv->insertOne($example);
        
        $content = $csv->toString();
        
        return response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_importacion_productos.csv"',
        ]);
    }
    
    /**
     * Validar archivo antes de importar (preview)
     */
    public function validateImport(Request $request)
    {
        $user = auth()->user();
        
        if (!$user || !$user->tenant_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
        ]);
        
        try {
            $file = $request->file('file');
            $path = $file->store('imports', 'local');
            $fullPath = storage_path('app/' . $path);
            
            $csv = Reader::createFromPath($fullPath, 'r');
            $csv->setHeaderOffset(0);
            $csv->setDelimiter(',');
            
            $records = $csv->getRecords();
            $preview = [];
            $errors = [];
            $rowCount = 0;
            
            foreach ($records as $index => $record) {
                $rowCount++;
                $rowNumber = $index + 2;
                
                if ($rowCount > 10) break; // Solo preview de primeras 10 filas
                
                $validation = $this->validateProductRow($record, $rowNumber, $user->tenant_id);
                
                $preview[] = [
                    'row' => $rowNumber,
                    'data' => [
                        'nombre' => $record['nombre'] ?? $record['name'] ?? '',
                        'sku' => $record['sku'] ?? $record['codigo'] ?? '',
                        'precio' => $record['precio'] ?? $record['price'] ?? '',
                        'stock_inicial' => $record['stock_inicial'] ?? $record['initial_stock'] ?? '',
                    ],
                    'valid' => $validation['valid'],
                    'error' => $validation['valid'] ? null : $validation['error'],
                ];
            }
            
            // Contar total de filas
            $totalRows = iterator_count($csv->getRecords()) + 1; // +1 por header
            
            Storage::delete($path);
            
            return response()->json([
                'preview' => $preview,
                'total_rows' => $totalRows,
                'can_import' => empty($errors),
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al validar: ' . $e->getMessage(),
            ], 500);
        }
    }
}
