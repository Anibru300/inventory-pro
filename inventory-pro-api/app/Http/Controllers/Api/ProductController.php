<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductPriceHistory;
use App\Models\StockLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'stockLevels.warehouse']);

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Stock status filter
        if ($request->has('stock_status')) {
            switch ($request->stock_status) {
                case 'low_stock':
                    $query->lowStock();
                    break;
                case 'out_of_stock':
                    $query->outOfStock();
                    break;
                case 'in_stock':
                    $query->whereHas('stockLevels', function ($q) {
                        $q->where('quantity', '>', 0);
                    });
                    break;
            }
        }

        $products = $query->latest()->paginate($request->per_page ?? 25);

        return response()->json($products);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'sku' => 'required|string|max:100|unique:products',
                'barcode' => 'nullable|string|max:100|unique:products',
                'description' => 'nullable|string',
                'category_id' => 'nullable|exists:categories,id',
                'unit' => 'nullable|string|max:50',
                'cost' => 'required|numeric|min:0',
                'price' => 'required|numeric|min:0',
                'min_stock' => 'nullable|numeric|min:0',
                'max_stock' => 'nullable|numeric|min:0',
                'initial_stock' => 'nullable|numeric|min:0',
                'warehouse_id' => 'nullable|exists:warehouses,id',
                'valuation_method' => 'nullable|in:FIFO,AVERAGE,LIFO',
                'is_active' => 'boolean',
                'image' => 'nullable|image|max:2048', // Max 2MB
            ], [
                'name.required' => 'El nombre del producto es obligatorio.',
                'sku.required' => 'El código SKU es obligatorio.',
                'sku.unique' => 'Este código SKU ya está en uso. Por favor, usa otro código.',
                'barcode.unique' => 'Este código de barras ya está registrado.',
                'cost.required' => 'El costo unitario es obligatorio.',
                'cost.numeric' => 'El costo debe ser un número.',
                'cost.min' => 'El costo no puede ser negativo.',
                'price.required' => 'El precio de venta es obligatorio.',
                'price.numeric' => 'El precio debe ser un número.',
                'price.min' => 'El precio no puede ser negativo.',
                'category_id.exists' => 'La categoría seleccionada no existe.',
                'warehouse_id.exists' => 'El almacén seleccionado no existe.',
                'image.image' => 'El archivo debe ser una imagen.',
                'image.max' => 'La imagen no puede superar los 2MB.',
            ]);

            $initialStock = $validated['initial_stock'] ?? 0;
            $warehouseId = $validated['warehouse_id'] ?? null;
            
            // Mapear campos del frontend a nombres de BD
            $productData = [
                'name' => $validated['name'],
                'sku' => $validated['sku'],
                'barcode' => $validated['barcode'] ?? null,
                'description' => $validated['description'] ?? null,
                'category_id' => $validated['category_id'] ?? null,
                'unit_of_measure' => $validated['unit'] ?? 'piece',
                'unit_cost' => $validated['cost'],
                'selling_price' => $validated['price'],
                'stock_min' => $validated['min_stock'] ?? 0,
                'stock_max' => $validated['max_stock'] ?? null,
                'reorder_point' => $validated['min_stock'] ?? 0,
                'valuation_method' => $validated['valuation_method'] ?? 'FIFO',
                'is_active' => $validated['is_active'] ?? true,
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
                $productData['images'] = [
                    [
                        'url' => Storage::url($path),
                        'path' => $path,
                        'is_primary' => true,
                    ]
                ];
            }

            // Create product
            $product = Product::create($productData);
            
            // Create stock level if warehouse specified - simplified
            if ($warehouseId && $initialStock > 0) {
                try {
                    StockLevel::create([
                        'tenant_id' => $product->tenant_id,
                        'product_id' => $product->id,
                        'warehouse_id' => $warehouseId,
                        'quantity' => $initialStock,
                        'reserved_quantity' => 0,
                    ]);
                } catch (\Exception $stockError) {
                    \Log::error('Error creating stock level: ' . $stockError->getMessage());
                    // Continue even if stock level fails - product is created
                }
            }

            return response()->json($product->load(['category', 'stockLevels.warehouse']), 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error creating product: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return response()->json([
                'message' => 'Error del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Product $product)
    {
        return response()->json($product->load([
            'category',
            'stockLevels.warehouse',
            'stockMovements' => function ($q) {
                $q->latest()->limit(20);
            }
        ]));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'sku' => 'sometimes|string|max:100|unique:products,sku,' . $product->id,
            'barcode' => 'nullable|string|max:100|unique:products,barcode,' . $product->id,
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'unit' => 'sometimes|string|max:50',
            'cost' => 'sometimes|numeric|min:0',
            'price' => 'sometimes|numeric|min:0',
            'min_stock' => 'nullable|numeric|min:0',
            'max_stock' => 'nullable|numeric|min:0',
            'valuation_method' => 'sometimes|in:FIFO,AVERAGE,LIFO',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'remove_image' => 'boolean',
        ]);

        // Mapear campos del frontend a nombres de BD
        $productData = [];
        if (isset($validated['name'])) $productData['name'] = $validated['name'];
        if (isset($validated['sku'])) $productData['sku'] = $validated['sku'];
        if (isset($validated['barcode'])) $productData['barcode'] = $validated['barcode'];
        if (isset($validated['description'])) $productData['description'] = $validated['description'];
        if (isset($validated['category_id'])) $productData['category_id'] = $validated['category_id'];
        if (isset($validated['unit'])) $productData['unit_of_measure'] = $validated['unit'];
        if (isset($validated['cost'])) $productData['unit_cost'] = $validated['cost'];
        if (isset($validated['price'])) $productData['selling_price'] = $validated['price'];
        if (isset($validated['min_stock'])) {
            $productData['stock_min'] = $validated['min_stock'];
            $productData['reorder_point'] = $validated['min_stock'];
        }
        if (isset($validated['max_stock'])) $productData['stock_max'] = $validated['max_stock'];
        if (isset($validated['valuation_method'])) $productData['valuation_method'] = $validated['valuation_method'];
        if (isset($validated['is_active'])) $productData['is_active'] = $validated['is_active'];

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if (!empty($product->images)) {
                foreach ($product->images as $img) {
                    if (isset($img['path'])) {
                        Storage::disk('public')->delete($img['path']);
                    }
                }
            }
            $path = $request->file('image')->store('products', 'public');
            $productData['images'] = [
                [
                    'url' => Storage::url($path),
                    'path' => $path,
                    'is_primary' => true,
                ]
            ];
        } elseif ($request->boolean('remove_image')) {
            // Remove image
            if (!empty($product->images)) {
                foreach ($product->images as $img) {
                    if (isset($img['path'])) {
                        Storage::disk('public')->delete($img['path']);
                    }
                }
            }
            $productData['images'] = null;
        }

        // Track price changes
        if (isset($productData['unit_cost']) || isset($productData['selling_price'])) {
            ProductPriceHistory::create([
                'tenant_id' => $product->tenant_id,
                'product_id' => $product->id,
                'changed_by' => auth()->id(),
                'old_cost' => $product->unit_cost,
                'new_cost' => $productData['unit_cost'] ?? $product->unit_cost,
                'old_price' => $product->selling_price,
                'new_price' => $productData['selling_price'] ?? $product->selling_price,
                'reason' => $request->input('price_change_reason', 'Actualización manual'),
            ]);
        }

        $product->update($productData);

        return response()->json($product->load(['category', 'stockLevels.warehouse']));
    }

    public function destroy(Product $product)
    {
        // Delete images
        if (!empty($product->images)) {
            foreach ($product->images as $img) {
                if (isset($img['path'])) {
                    Storage::disk('public')->delete($img['path']);
                }
            }
        }

        // Check if product has stock movements
        if ($product->stockMovements()->count() > 0) {
            // Soft delete instead of hard delete
            $product->delete();
            return response()->json(['message' => 'Product archived']);
        }

        $product->forceDelete();
        return response()->json(['message' => 'Product deleted']);
    }

    public function lowStock()
    {
        $products = Product::with(['category', 'stockLevels.warehouse'])
            ->lowStock()
            ->get();

        return response()->json($products);
    }

    /**
     * Búsqueda global de productos
     */
    public function searchGlobal(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::with(['category', 'stockLevels.warehouse'])
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%")
                  ->orWhere('barcode', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->active()
            ->limit(10)
            ->get();

        return response()->json($products);
    }

    /**
     * Importación masiva desde Excel/CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:10240',
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        
        $results = [
            'created' => 0,
            'updated' => 0,
            'errors' => [],
        ];

        // Parse CSV
        if ($extension === 'csv') {
            $handle = fopen($file->getPathname(), 'r');
            $header = fgetcsv($handle);
            
            $rowNumber = 1;
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;
                $data = array_combine($header, $row);
                
                $result = $this->processImportRow($data);
                if ($result['success']) {
                    if ($result['action'] === 'created') {
                        $results['created']++;
                    } else {
                        $results['updated']++;
                    }
                } else {
                    $results['errors'][] = "Fila {$rowNumber}: " . $result['error'];
                }
            }
            fclose($handle);
        }

        return response()->json($results);
    }

    private function processImportRow(array $data): array
    {
        try {
            $sku = $data['sku'] ?? $data['SKU'] ?? null;
            $name = $data['name'] ?? $data['nombre'] ?? $data['Nombre'] ?? null;
            
            if (!$sku || !$name) {
                return ['success' => false, 'error' => 'SKU y Nombre son requeridos'];
            }

            $productData = [
                'name' => $name,
                'sku' => $sku,
                'barcode' => $data['barcode'] ?? $data['codigo_barras'] ?? null,
                'description' => $data['description'] ?? $data['descripcion'] ?? null,
                'unit_cost' => floatval($data['cost'] ?? $data['costo'] ?? 0),
                'selling_price' => floatval($data['price'] ?? $data['precio'] ?? 0),
                'stock_min' => intval($data['min_stock'] ?? $data['stock_minimo'] ?? 0),
                'unit_of_measure' => $data['unit'] ?? $data['unidad'] ?? 'piece',
                'is_active' => true,
            ];

            // Check if product exists
            $existing = Product::where('sku', $sku)->first();
            
            if ($existing) {
                $existing->update($productData);
                return ['success' => true, 'action' => 'updated'];
            } else {
                Product::create($productData);
                return ['success' => true, 'action' => 'created'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Descargar template de importación
     */
    public function downloadTemplate()
    {
        $headers = ['sku', 'name', 'description', 'barcode', 'cost', 'price', 'min_stock', 'unit'];
        $csv = implode(',', $headers) . "\n";
        $csv .= "PROD-001,Producto Ejemplo,Descripción opcional,7501234567890,100.00,150.00,10,piece\n";
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="template_productos.csv"');
    }

    /**
     * Reporte ABC de productos
     */
    public function abcAnalysis(Request $request)
    {
        $products = Product::with(['stockLevels', 'stockMovements'])
            ->whereHas('stockMovements')
            ->get()
            ->map(function ($product) {
                $totalValue = $product->stockLevels->sum(function ($level) {
                    return $level->quantity * $level->avg_unit_cost;
                });
                
                $movementCount = $product->stockMovements->count();
                $totalQuantity = $product->stockMovements->sum('quantity');
                
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'category' => $product->category?->name,
                    'stock_quantity' => $product->total_stock,
                    'stock_value' => $totalValue,
                    'movement_count' => $movementCount,
                    'total_quantity_moved' => abs($totalQuantity),
                    'avg_monthly_movement' => round(abs($totalQuantity) / 12, 2),
                ];
            })
            ->sortByDesc('stock_value')
            ->values();

        // Calcular totales para porcentajes
        $totalValue = $products->sum('stock_value');
        $totalMovements = $products->sum('movement_count');
        
        $accumulatedValue = 0;
        $accumulatedPercentage = 0;
        
        $classified = $products->map(function ($product) use ($totalValue, &$accumulatedValue, &$accumulatedPercentage) {
            $accumulatedValue += $product['stock_value'];
            $accumulatedPercentage = ($accumulatedValue / $totalValue) * 100;
            
            // Clasificación ABC
            if ($accumulatedPercentage <= 80) {
                $class = 'A';
            } elseif ($accumulatedPercentage <= 95) {
                $class = 'B';
            } else {
                $class = 'C';
            }
            
            return array_merge($product, [
                'percentage_value' => round(($product['stock_value'] / $totalValue) * 100, 2),
                'accumulated_percentage' => round($accumulatedPercentage, 2),
                'abc_class' => $class,
            ]);
        });

        return response()->json([
            'products' => $classified,
            'summary' => [
                'total_products' => $products->count(),
                'total_value' => $totalValue,
                'class_a' => $classified->where('abc_class', 'A')->count(),
                'class_b' => $classified->where('abc_class', 'B')->count(),
                'class_c' => $classified->where('abc_class', 'C')->count(),
            ],
        ]);
    }

    /**
     * Historial de precios del producto
     */
    public function priceHistory(Product $product)
    {
        $history = $product->priceHistory()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($history);
    }

    /**
     * Get all stock levels for offline sync
     */
    public function getAllStockLevels(Request $request)
    {
        $tenantId = $request->user()->tenant_id;
        
        $stockLevels = StockLevel::where('tenant_id', $tenantId)
            ->with(['product', 'warehouse'])
            ->get()
            ->map(function ($level) {
                return [
                    'id' => $level->id,
                    'product_id' => $level->product_id,
                    'warehouse_id' => $level->warehouse_id,
                    'quantity' => $level->quantity,
                    'product' => [
                        'id' => $level->product?->id,
                        'name' => $level->product?->name,
                        'sku' => $level->product?->sku,
                    ],
                    'warehouse' => [
                        'id' => $level->warehouse?->id,
                        'name' => $level->warehouse?->name,
                    ],
                ];
            });

        return response()->json($stockLevels);
    }
}
