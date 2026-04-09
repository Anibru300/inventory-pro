<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductPriceHistory;
use App\Models\StockLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Cache TTL en segundos (5 minutos)
     */
    private const CACHE_TTL = 300;

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
            // Generar cache key única basada en tenant y parámetros
            $cacheKey = "products:{$user->tenant_id}:" . md5(serialize($request->all()));
            
            // Intentar obtener de caché
            $cached = Cache::get($cacheKey);
            if ($cached) {
                return response()->json($cached);
            }
            
            // Query optimizada con índices
            $query = DB::table('products')
                ->where('tenant_id', $user->tenant_id)
                ->whereNull('deleted_at');
            
            // Filtros opcionales
            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('name', 'ilike', "%{$search}%")
                      ->orWhere('sku', 'ilike', "%{$search}%")
                      ->orWhere('barcode', 'ilike', "%{$search}%");
                });
            }
            
            if ($request->has('category_id')) {
                $query->where('category_id', $request->get('category_id'));
            }
            
            if ($request->has('status')) {
                switch ($request->get('status')) {
                    case 'active':
                        $query->where('is_active', true);
                        break;
                    case 'inactive':
                        $query->where('is_active', false);
                        break;
                }
            }
            
            // Ordenamiento
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $allowedSortColumns = ['name', 'sku', 'created_at', 'updated_at', 'price'];
            
            if (in_array($sortBy, $allowedSortColumns)) {
                $query->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
            }
            
            // Paginación con límite máximo
            $perPage = min($request->get('per_page', 25), 100);
            $products = $query->paginate($perPage);
            
            // Agregar datos de stock para cada producto (query optimizada)
            $productIds = collect($products->items())->pluck('id');
            
            if ($productIds->isNotEmpty()) {
                $stockData = DB::table('stock_levels')
                    ->whereIn('product_id', $productIds)
                    ->where('tenant_id', $user->tenant_id)
                    ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                    ->groupBy('product_id')
                    ->get()
                    ->keyBy('product_id');
                
                // Agregar stock a cada producto
                foreach ($products->items() as $product) {
                    $stock = $stockData->get($product->id);
                    $product->stock_quantity = $stock ? (int) $stock->total_quantity : 0;
                    $product->stock_status = $this->getStockStatus(
                        $product->stock_quantity, 
                        $product->min_stock ?? 0
                    );
                }
            }
            
            $result = $products->toArray();
            
            // Guardar en caché
            Cache::put($cacheKey, $result, self::CACHE_TTL);
            
            return response()->json($result);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cargar productos: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
    
    /**
     * Invalidar caché de productos para un tenant
     */
    private function clearProductsCache($tenantId)
    {
        // En caché file no podemos borrar por patrón fácilmente
        // Pero podemos usar el sistema de tags de Laravel si está disponible
        try {
            // Intentar borrar con tags (funciona con Redis/Array)
            Cache::tags(["products:{$tenantId}"])->flush();
        } catch (\Exception $e) {
            // Si no soporta tags, la caché expirará en CACHE_TTL segundos
            // Como workaround, podemos incrementar un "version" en caché
            Cache::forget("products:{$tenantId}:version");
        }
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
                'sku' => 'required|string|max:100|unique:products,sku,NULL,id,tenant_id,' . $user->tenant_id,
                'barcode' => 'nullable|string|max:100|unique:products,barcode,NULL,id,tenant_id,' . $user->tenant_id,
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
            
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
            }
            
            // Set defaults
            $validated['tenant_id'] = $user->tenant_id;
            $validated['is_active'] = $validated['is_active'] ?? true;
            $validated['valuation_method'] = $validated['valuation_method'] ?? 'AVERAGE';
            $validated['image'] = $imagePath;
            
            // Create product
            $product = Product::create($validated);
            
            // Invalidar caché de productos para que aparezca en el listado
            $this->clearProductsCache($user->tenant_id);
            
            // Create initial stock if provided
            if (!empty($validated['initial_stock']) && $validated['initial_stock'] > 0) {
                $warehouseId = $validated['warehouse_id'] ?? $this->getPrimaryWarehouseId($user->tenant_id);
                
                StockLevel::create([
                    'tenant_id' => $user->tenant_id,
                    'product_id' => $product->id,
                    'warehouse_id' => $warehouseId,
                    'quantity' => $validated['initial_stock'],
                    'avg_unit_cost' => $validated['cost'],
                ]);
            }
            
            return response()->json([
                'message' => 'Producto creado exitosamente',
                'product' => $product->load(['category', 'stockLevels.warehouse'])
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear producto: ' . $e->getMessage(),
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
            $cacheKey = "product:{$user->tenant_id}:{$id}";
            
            $cached = Cache::get($cacheKey);
            if ($cached) {
                return response()->json($cached);
            }
            
            $product = Product::with([
                    'category',
                    'stockLevels.warehouse',
                    'images'
                ])
                ->where('id', $id)
                ->where('tenant_id', $user->tenant_id)
                ->first();
            
            if (!$product) {
                return response()->json(['message' => 'Producto no encontrado'], 404);
            }
            
            // Calculate total stock
            $product->total_stock = $product->stockLevels->sum('quantity');
            $product->stock_status = $this->getStockStatus(
                $product->total_stock, 
                $product->min_stock ?? 0
            );
            
            $result = $product->toArray();
            Cache::put($cacheKey, $result, self::CACHE_TTL);
            
            return response()->json($result);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cargar producto: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        
        if (!$user || !$user->tenant_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        
        try {
            $product = Product::where('id', $id)
                ->where('tenant_id', $user->tenant_id)
                ->first();
            
            if (!$product) {
                return response()->json(['message' => 'Producto no encontrado'], 404);
            }
            
            $validated = $request->validate([
                'name' => 'string|max:255',
                'sku' => 'string|max:100|unique:products,sku,' . $id . ',id,tenant_id,' . $user->tenant_id,
                'barcode' => 'nullable|string|max:100|unique:products,barcode,' . $id . ',id,tenant_id,' . $user->tenant_id,
                'description' => 'nullable|string',
                'category_id' => 'nullable|exists:categories,id',
                'unit' => 'nullable|string|max:50',
                'cost' => 'numeric|min:0',
                'price' => 'numeric|min:0',
                'min_stock' => 'nullable|numeric|min:0',
                'max_stock' => 'nullable|numeric|min:0',
                'is_active' => 'boolean',
                'image' => 'nullable|image|max:2048',
            ]);
            
            // Track price changes
            if (isset($validated['price']) && $validated['price'] != $product->price) {
                ProductPriceHistory::create([
                    'tenant_id' => $user->tenant_id,
                    'product_id' => $product->id,
                    'old_price' => $product->price,
                    'new_price' => $validated['price'],
                    'changed_by' => $user->id,
                    'reason' => $request->input('price_change_reason', 'Actualización manual'),
                ]);
            }
            
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $validated['image'] = $request->file('image')->store('products', 'public');
            }
            
            $product->update($validated);
            
            // Invalidar caché del producto y del listado
            Cache::forget("product:{$user->tenant_id}:{$id}");
            $this->clearProductsCache($user->tenant_id);
            
            return response()->json([
                'message' => 'Producto actualizado exitosamente',
                'product' => $product->fresh(['category', 'stockLevels.warehouse'])
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar producto: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $user = auth()->user();
        
        if (!$user || !$user->tenant_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        
        try {
            $product = Product::where('id', $id)
                ->where('tenant_id', $user->tenant_id)
                ->first();
            
            if (!$product) {
                return response()->json(['message' => 'Producto no encontrado'], 404);
            }
            
            // Soft delete
            $product->delete();
            
            // Invalidar caché del producto y del listado
            Cache::forget("product:{$user->tenant_id}:{$id}");
            $this->clearProductsCache($user->tenant_id);
            
            return response()->json([
                'message' => 'Producto eliminado exitosamente'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar producto: ' . $e->getMessage()
            ], 500);
        }
    }
    
    private function getPrimaryWarehouseId($tenantId)
    {
        $warehouse = DB::table('warehouses')
            ->where('tenant_id', $tenantId)
            ->where('is_primary', true)
            ->first();
            
        return $warehouse?->id;
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
}
