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
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }
        
        if (!$user->tenant_id) {
            return response()->json([
                'message' => 'Usuario sin empresa asignada',
                'requires_tenant' => true
            ], 403);
        }
        
        try {
            // Build query with joins for category and stock
            $query = DB::table('products')
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->leftJoin('stock_levels', 'products.id', '=', 'stock_levels.product_id')
                ->select(
                    'products.*',
                    'categories.name as category_name',
                    DB::raw('COALESCE(SUM(stock_levels.quantity), 0) as total_stock'),
                    DB::raw('COALESCE(SUM(stock_levels.available_quantity), 0) as available_stock')
                )
                ->where('products.tenant_id', $user->tenant_id)
                ->whereNull('products.deleted_at')
                ->groupBy('products.id', 'categories.name')
                ->orderBy('products.created_at', 'desc');
            
            // Apply search filter if provided
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('products.name', 'ilike', "%{$search}%")
                      ->orWhere('products.sku', 'ilike', "%{$search}%")
                      ->orWhere('products.barcode', 'ilike', "%{$search}%");
                });
            }
            
            // Apply category filter if provided
            if ($request->has('category_id') && $request->category_id) {
                $query->where('products.category_id', $request->category_id);
            }
            
            $products = $query->paginate(25);
            
            // Transform the response to match frontend expectations
            $transformed = $products->through(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'barcode' => $product->barcode,
                    'description' => $product->description,
                    'category_id' => $product->category_id,
                    'category' => $product->category_name ? ['id' => $product->category_id, 'name' => $product->category_name] : null,
                    'unit_of_measure' => $product->unit_of_measure,
                    'unit_cost' => $product->unit_cost,
                    'selling_price' => $product->selling_price,
                    'stock_min' => $product->stock_min,
                    'stock_max' => $product->stock_max,
                    'total_stock' => (int) $product->total_stock,
                    'available_stock' => (int) $product->available_stock,
                    'images' => $product->images ? json_decode($product->images, true) : [],
                    'is_active' => $product->is_active,
                    'valuation_method' => $product->valuation_method,
                    'created_at' => $product->created_at,
                    'updated_at' => $product->updated_at,
                    'stock_status' => $this->getStockStatus($product->total_stock, $product->stock_min),
                ];
            });
            
            return response()->json($transformed);
        } catch (\Exception $e) {
            \Log::error('Error fetching products: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al cargar productos: ' . $e->getMessage()
            ], 500);
        }
    }
    
    private function getStockStatus($stock, $minStock)
    {
        $stock = (int) $stock;
        $minStock = (int) $minStock;
        
        if ($stock <= 0) {
            return 'out_of_stock';
        }
        if ($minStock > 0 && $stock <= $minStock) {
            return 'low_stock';
        }
        return 'ok';
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        if (!$user || !$user->tenant_id) {
            return response()->json(['message' => 'Usuario no autenticado o sin empresa'], 403);
        }
        
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
                'image' => 'nullable|image|max:2048',
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
            ]);

            DB::beginTransaction();
            
            $product = new Product();
            $product->tenant_id = $user->tenant_id;
            $product->name = $validated['name'];
            $product->sku = $validated['sku'];
            $product->barcode = $validated['barcode'] ?? null;
            $product->description = $validated['description'] ?? null;
            $product->category_id = $validated['category_id'] ?? null;
            $product->unit_of_measure = $validated['unit'] ?? 'piece';
            $product->unit_cost = $validated['cost'];
            $product->selling_price = $validated['price'];
            $product->stock_min = $validated['min_stock'] ?? 0;
            $product->stock_max = $validated['max_stock'] ?? null;
            $product->reorder_point = $validated['min_stock'] ?? 0;
            $product->valuation_method = $validated['valuation_method'] ?? 'FIFO';
            $product->is_active = $validated['is_active'] ?? true;
            $product->save();

            // Handle image upload
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
                $product->images = [['url' => Storage::url($path), 'is_primary' => true]];
                $product->save();
            }

            // Create initial stock if provided
            if (!empty($validated['initial_stock']) && $validated['initial_stock'] > 0) {
                $warehouseId = $validated['warehouse_id'] ?? $this->getDefaultWarehouseId($user->tenant_id);
                
                if ($warehouseId) {
                    StockLevel::create([
                        'tenant_id' => $user->tenant_id,
                        'product_id' => $product->id,
                        'warehouse_id' => $warehouseId,
                        'quantity' => $validated['initial_stock'],
                        'available_quantity' => $validated['initial_stock'],
                    ]);

                    // Create price history entry
                    ProductPriceHistory::create([
                        'tenant_id' => $user->tenant_id,
                        'product_id' => $product->id,
                        'unit_cost' => $validated['cost'],
                        'selling_price' => $validated['price'],
                        'movement_type' => 'initial',
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'product' => $product->fresh(),
                'message' => 'Producto creado exitosamente'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating product: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al crear el producto: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $user = auth()->user();
        
        if (!$user || !$user->tenant_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        
        try {
            $product = DB::table('products')
                ->where('id', $id)
                ->where('tenant_id', $user->tenant_id)
                ->whereNull('deleted_at')
                ->first();
            
            if (!$product) {
                return response()->json(['message' => 'Producto no encontrado'], 404);
            }
            
            return response()->json($product);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        
        if (!$user || !$user->tenant_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        
        $product = Product::where('id', $id)
            ->where('tenant_id', $user->tenant_id)
            ->first();
        
        if (!$product) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku,' . $id,
            'barcode' => 'nullable|string|max:100|unique:products,barcode,' . $id,
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'unit' => 'nullable|string|max:50',
            'cost' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'min_stock' => 'nullable|numeric|min:0',
            'max_stock' => 'nullable|numeric|min:0',
            'valuation_method' => 'nullable|in:FIFO,AVERAGE,LIFO',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        $product->name = $validated['name'];
        $product->sku = $validated['sku'];
        $product->barcode = $validated['barcode'] ?? null;
        $product->description = $validated['description'] ?? null;
        $product->category_id = $validated['category_id'] ?? null;
        $product->unit_of_measure = $validated['unit'] ?? $product->unit_of_measure;
        $product->unit_cost = $validated['cost'];
        $product->selling_price = $validated['price'];
        $product->stock_min = $validated['min_stock'] ?? 0;
        $product->stock_max = $validated['max_stock'] ?? null;
        $product->reorder_point = $validated['min_stock'] ?? 0;
        $product->valuation_method = $validated['valuation_method'] ?? $product->valuation_method;
        $product->is_active = $validated['is_active'] ?? $product->is_active;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->images = [['url' => Storage::url($path), 'is_primary' => true]];
        }

        $product->save();

        return response()->json([
            'product' => $product,
            'message' => 'Producto actualizado exitosamente'
        ]);
    }

    public function destroy($id)
    {
        $user = auth()->user();
        
        if (!$user || !$user->tenant_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        
        $product = Product::where('id', $id)
            ->where('tenant_id', $user->tenant_id)
            ->first();
        
        if (!$product) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Producto eliminado exitosamente']);
    }

    protected function getDefaultWarehouseId($tenantId)
    {
        $warehouse = DB::table('warehouses')
            ->where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->first();
        
        return $warehouse?->id;
    }

    // Additional methods (lowStock, etc.) would go here...
    public function lowStock()
    {
        $user = auth()->user();
        
        if (!$user || !$user->tenant_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        
        // Simplified low stock query
        $products = DB::table('products')
            ->where('tenant_id', $user->tenant_id)
            ->whereNull('deleted_at')
            ->get();
        
        $lowStockProducts = [];
        foreach ($products as $product) {
            $totalStock = DB::table('stock_levels')
                ->where('product_id', $product->id)
                ->sum('quantity');
            
            if ($totalStock > 0 && $totalStock <= $product->stock_min) {
                $product->total_stock = $totalStock;
                $lowStockProducts[] = $product;
            }
        }
        
        return response()->json($lowStockProducts);
    }
}
