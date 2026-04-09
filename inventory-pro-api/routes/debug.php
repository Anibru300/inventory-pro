<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\Warehouse;

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
