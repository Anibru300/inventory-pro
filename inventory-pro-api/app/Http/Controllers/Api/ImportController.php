<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockLevel;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ImportController extends Controller
{
    /**
     * Import products from CSV/Excel data
     */
    public function importProducts(Request $request)
    {
        $validated = $request->validate([
            'products' => 'required|array|min:1',
            'products.*.name' => 'required|string|max:255',
            'products.*.sku' => 'required|string|max:100',
            'products.*.category' => 'nullable|string',
            'products.*.warehouse' => 'nullable|string',
            'products.*.quantity' => 'nullable|numeric|min:0',
            'products.*.cost' => 'nullable|numeric|min:0',
            'products.*.price' => 'nullable|numeric|min:0',
            'products.*.min_stock' => 'nullable|numeric|min:0',
        ]);

        $results = [
            'created' => 0,
            'updated' => 0,
            'errors' => [],
        ];

        DB::beginTransaction();

        try {
            foreach ($validated['products'] as $index => $row) {
                try {
                    // Get or create category
                    $categoryId = null;
                    if (!empty($row['category'])) {
                        $category = Category::firstOrCreate(
                            ['name' => $row['category']],
                            ['color' => '#3b82f6']
                        );
                        $categoryId = $category->id;
                    }

                    // Get or use default warehouse
                    $warehouseId = null;
                    if (!empty($row['warehouse'])) {
                        $warehouse = Warehouse::where('name', $row['warehouse'])->first();
                        if ($warehouse) {
                            $warehouseId = $warehouse->id;
                        }
                    }
                    
                    // Use first warehouse if not specified
                    if (!$warehouseId) {
                        $defaultWarehouse = Warehouse::first();
                        if ($defaultWarehouse) {
                            $warehouseId = $defaultWarehouse->id;
                        }
                    }

                    // Check if product exists by SKU
                    $existingProduct = Product::where('sku', $row['sku'])->first();

                    if ($existingProduct) {
                        // Update existing product
                        $existingProduct->update([
                            'name' => $row['name'],
                            'category_id' => $categoryId ?? $existingProduct->category_id,
                            'unit_cost' => $row['cost'] ?? $existingProduct->unit_cost,
                            'selling_price' => $row['price'] ?? $existingProduct->selling_price,
                            'stock_min' => $row['min_stock'] ?? $existingProduct->stock_min,
                        ]);
                        $results['updated']++;
                    } else {
                        // Create new product
                        $product = Product::create([
                            'name' => $row['name'],
                            'sku' => $row['sku'],
                            'category_id' => $categoryId,
                            'unit_of_measure' => 'piece',
                            'unit_cost' => $row['cost'] ?? 0,
                            'selling_price' => $row['price'] ?? 0,
                            'stock_min' => $row['min_stock'] ?? 0,
                            'reorder_point' => $row['min_stock'] ?? 0,
                            'valuation_method' => 'FIFO',
                            'is_active' => true,
                        ]);

                        // Create stock level if quantity provided
                        $quantity = $row['quantity'] ?? 0;
                        if ($warehouseId && $quantity > 0) {
                            StockLevel::create([
                                'product_id' => $product->id,
                                'warehouse_id' => $warehouseId,
                                'quantity' => $quantity,
                                'available_quantity' => $quantity,
                                'reserved_quantity' => 0,
                                'reorder_point' => $row['min_stock'] ?? 0,
                            ]);
                        }

                        $results['created']++;
                    }
                } catch (\Exception $e) {
                    $results['errors'][] = [
                        'row' => $index + 1,
                        'sku' => $row['sku'] ?? 'N/A',
                        'error' => $e->getMessage(),
                    ];
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Importación completada',
                'results' => $results,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error en la importación',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Download import template
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="plantilla_productos.csv"',
        ];

        $content = "nombre,sku,categoria,almacen,cantidad,costo,precio_venta,stock_minimo
"
                 . "Producto Ejemplo,PROD001,Electrónicos,Almacén Principal,100,50.00,75.00,10
"
                 . "Segundo Producto,PROD002,Ropa,Almacén Principal,50,30.00,45.00,5";

        return response($content, 200, $headers);
    }
}