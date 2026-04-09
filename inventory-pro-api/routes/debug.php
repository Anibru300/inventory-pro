<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\Warehouse;
use App\Models\StockMovement;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

// Debug endpoint para verificar headers
Route::get('/debug/headers', function (Request $request) {
    return response()->json([
        'message' => 'Debug headers endpoint',
        'headers' => $request->headers->all(),
        'server' => [
            'HTTP_AUTHORIZATION' => $_SERVER['HTTP_AUTHORIZATION'] ?? 'NO SET',
            'HTTP_X_AUTH_TOKEN' => $_SERVER['HTTP_X_AUTH_TOKEN'] ?? 'NO SET',
            'REDIRECT_HTTP_AUTHORIZATION' => $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? 'NO SET',
        ],
        'bearer_token' => $request->bearerToken() ?? 'NO TOKEN',
        'auth_check' => auth()->check(),
        'user' => auth()->user()?->email ?? 'NO USER',
    ]);
});

// Test endpoint protegido
Route::middleware('auth:sanctum')->get('/debug/auth-test', function (Request $request) {
    return response()->json([
        'message' => 'Auth successful',
        'user' => $request->user()->email,
    ]);
});

// Debug específico para warehouses
Route::middleware('auth:sanctum')->get('/debug/warehouses', function (Request $request) {
    $user = auth()->user();
    $debug = [
        'user_id' => $user?->id,
        'user_email' => $user?->email,
        'tenant_id' => $user?->tenant_id,
        'auth_check' => auth()->check(),
    ];
    
    try {
        // Verificar si existe la tabla warehouses
        $hasWarehousesTable = DB::getSchemaBuilder()->hasTable('warehouses');
        $debug['has_warehouses_table'] = $hasWarehousesTable;
        
        if ($hasWarehousesTable) {
            // Contar almacenes para este tenant
            $count = Warehouse::where('tenant_id', $user?->tenant_id)->count();
            $debug['warehouses_count_for_tenant'] = $count;
            
            // Traer los almacenes sin procesar
            $warehouses = Warehouse::where('tenant_id', $user?->tenant_id)->get();
            $debug['warehouses_raw'] = $warehouses->toArray();
        }
        
        // Verificar si existe la tabla stock_levels
        $hasStockLevelsTable = DB::getSchemaBuilder()->hasTable('stock_levels');
        $debug['has_stock_levels_table'] = $hasStockLevelsTable;
        
        return response()->json([
            'success' => true,
            'debug' => $debug
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
            'debug' => $debug
        ], 500);
    }
});

// Debug específico para stock movements summary
Route::middleware('auth:sanctum')->get('/debug/stock-movements-summary', function (Request $request) {
    $user = auth()->user();
    $debug = [
        'user_id' => $user?->id,
        'tenant_id' => $user?->tenant_id,
        'auth_check' => auth()->check(),
    ];
    
    try {
        // Verificar si existe la tabla
        $hasTable = DB::getSchemaBuilder()->hasTable('stock_movements');
        $debug['has_stock_movements_table'] = $hasTable;
        
        if (!$hasTable) {
            return response()->json([
                'success' => false,
                'error' => 'La tabla stock_movements no existe',
                'debug' => $debug
            ], 500);
        }
        
        // Verificar columnas
        $columns = DB::getSchemaBuilder()->getColumnListing('stock_movements');
        $debug['table_columns'] = $columns;
        
        // Verificar métodos del modelo
        $debug['entry_types'] = StockMovement::getEntryTypes();
        $debug['exit_types'] = StockMovement::getExitTypes();
        
        // Intentar query simple
        $count = StockMovement::where('tenant_id', $user?->tenant_id)->count();
        $debug['total_movements'] = $count;
        
        // Intentar query de entradas
        $entries = StockMovement::where('tenant_id', $user?->tenant_id)
            ->whereIn('movement_type', StockMovement::getEntryTypes())
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('quantity');
        $debug['entries_sum'] = $entries;
        
        return response()->json([
            'success' => true,
            'debug' => $debug
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
            'debug' => $debug
        ], 500);
    }
});

// Debug específico para productos - VER TODOS LOS DATOS
Route::middleware('auth:sanctum')->get('/debug/products-all', function (Request $request) {
    $user = auth()->user();
    
    try {
        // TODOS los productos (incluyendo eliminados)
        $allProducts = Product::where('tenant_id', $user->tenant_id)
            ->withTrashed() // Incluir eliminados
            ->get(['id', 'name', 'sku', 'is_active', 'deleted_at', 'created_at']);
        
        // Solo activos (no eliminados)
        $activeProducts = Product::where('tenant_id', $user->tenant_id)
            ->whereNull('deleted_at')
            ->get(['id', 'name', 'sku', 'is_active', 'created_at']);
        
        // Verificar caché
        $cacheKey = "products:{$user->tenant_id}:" . md5(serialize([]));
        $cached = Cache::get($cacheKey);
        
        return response()->json([
            'success' => true,
            'summary' => [
                'total_including_deleted' => $allProducts->count(),
                'active_only' => $activeProducts->count(),
                'deleted_count' => $allProducts->where('deleted_at', '!=', null)->count(),
                'has_cache' => $cached !== null,
            ],
            'all_products' => $allProducts->toArray(),
            'active_products' => $activeProducts->toArray(),
            'user_tenant_id' => $user->tenant_id,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500);
    }
});

// Debug: Limpiar caché de productos manualmente
Route::middleware('auth:sanctum')->post('/debug/clear-products-cache', function (Request $request) {
    $user = auth()->user();
    
    try {
        // Limpiar todas las entradas de caché que contengan "products:{tenant_id}"
        Cache::flush(); // Nuclear option - limpia TODA la caché
        
        return response()->json([
            'success' => true,
            'message' => 'Caché de productos limpiada completamente',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
        ], 500);
    }
});
