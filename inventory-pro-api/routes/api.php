<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\InventoryEventController;
use App\Http\Controllers\Api\KardexController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductLotController;
use App\Http\Controllers\Api\StockMovementController;
use App\Http\Controllers\Api\WarehouseController;
use App\Http\Controllers\Api\WarehouseTransferController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Simple test endpoint
Route::get('/test', function () {
    try {
        // Test database connection
        $dbVersion = DB::select('SELECT sqlite_version() as version')[0]->version ?? 'unknown';
        $tenantCount = \App\Models\Tenant::count();
        $userCount = \App\Models\User::count();
        
        return response()->json([
            'status' => 'ok',
            'database' => 'connected',
            'sqlite_version' => $dbVersion,
            'tenants' => $tenantCount,
            'users' => $userCount,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ], 500);
    }
});

// Health check endpoint
Route::get('/health', function () {
    try {
        // Verificar conexión a base de datos
        $dbStatus = 'ok';
        $dbError = null;
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $dbStatus = 'error';
            $dbError = $e->getMessage();
        }
        
        return response()->json([
            'status' => 'ok',
            'timestamp' => now()->toIso8601String(),
            'version' => '1.0.0',
            'database' => $dbStatus,
            'database_error' => $dbError,
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'error' => $e->getMessage(),
        ], 500);
    }
});

// TEMPORAL: Ver logs de Laravel (últimos errores)
Route::get('/view-logs', function () {
    try {
        $logFile = storage_path('logs/laravel.log');
        
        // Si no existe, crearlo
        if (!file_exists($logFile)) {
            $dir = dirname($logFile);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            file_put_contents($logFile, "# Log created at " . now() . "\n");
        }
        
        // Leer últimas líneas
        $content = file_get_contents($logFile);
        $lines = explode("\n", $content);
        $lastLines = array_slice($lines, -100);
        
        // Filtrar solo errores relevantes
        $errorLines = array_filter($lastLines, function($line) {
            return strpos($line, 'ERROR') !== false || 
                   strpos($line, 'register') !== false ||
                   strpos($line, 'TenantSeeder') !== false ||
                   strpos($line, 'ERR_') !== false;
        });
        
        return response()->json([
            'log_file' => $logFile,
            'exists' => file_exists($logFile),
            'writable' => is_writable($logFile),
            'total_lines' => count($lines),
            'error_lines' => count($errorLines),
            'recent_errors' => array_values($errorLines),
            'raw_content' => implode("\n", array_slice($lines, -30))
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()], 500);
    }
});

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('/dashboard/advanced-stats', [DashboardController::class, 'advancedStats']);
    
    // Products
    Route::apiResource('products', ProductController::class);
    Route::get('/products/low-stock', [ProductController::class, 'lowStock']);
    
    // Categories
    Route::apiResource('categories', CategoryController::class);
    
    // Warehouses
    Route::apiResource('warehouses', WarehouseController::class);
    
    // Stock Movements
    Route::apiResource('stock-movements', StockMovementController::class);
    Route::get('/products/{product}/kardex', [StockMovementController::class, 'kardex']);
    
    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/inventory-valuation', [\App\Http\Controllers\Api\ReportController::class, 'inventoryValuation']);
        Route::get('/movements', [\App\Http\Controllers\Api\ReportController::class, 'movementsReport']);
        Route::get('/low-stock', [\App\Http\Controllers\Api\ReportController::class, 'lowStock']);
        Route::get('/top-products', [\App\Http\Controllers\Api\ReportController::class, 'topProducts']);
    });
    
    // Import
    Route::post('/import/products', [\App\Http\Controllers\Api\ImportController::class, 'importProducts']);
    Route::get('/import/template', [\App\Http\Controllers\Api\ImportController::class, 'downloadTemplate']);
    
    // Receipts (Vales)
    Route::apiResource('receipts', \App\Http\Controllers\Api\ReceiptController::class);
    Route::get('/receipts/{receipt}/pdf', [\App\Http\Controllers\Api\ReceiptController::class, 'generatePdf']);
    Route::get('/receipts/{receipt}/preview', [\App\Http\Controllers\Api\ReceiptController::class, 'previewPdf']);
    Route::patch('/receipts/{receipt}/recipient', [\App\Http\Controllers\Api\ReceiptController::class, 'updateRecipient']);
    Route::get('/receipts/statistics', [\App\Http\Controllers\Api\ReceiptController::class, 'statistics']);
    
    // Product Lots
    Route::apiResource('product-lots', ProductLotController::class);
    Route::get('/product-lots/available/list', [ProductLotController::class, 'getAvailableLots']);
    Route::get('/product-lots/expiring/list', [ProductLotController::class, 'getExpiringLots']);
    Route::get('/product-lots/stats/overview', [ProductLotController::class, 'getLotStats']);
    
    // Warehouse Transfers
    Route::apiResource('warehouse-transfers', WarehouseTransferController::class);
    Route::post('/warehouse-transfers/{transfer}/send', [WarehouseTransferController::class, 'send']);
    Route::post('/warehouse-transfers/{transfer}/receive', [WarehouseTransferController::class, 'receive']);
    Route::post('/warehouse-transfers/{transfer}/cancel', [WarehouseTransferController::class, 'cancel']);
    Route::get('/warehouse-transfers/stats/overview', [WarehouseTransferController::class, 'stats']);
    
    // Kardex & Reports
    Route::get('/kardex', [KardexController::class, 'getKardex']);
    Route::get('/kardex/valuation', [KardexController::class, 'getValuation']);
    Route::get('/kardex/movements-by-type', [KardexController::class, 'getMovementsByType']);
    Route::get('/kardex/inventory-turnover', [KardexController::class, 'getInventoryTurnover']);
    
    // Inventory Events
    Route::apiResource('inventory-events', InventoryEventController::class)->only(['index', 'show', 'destroy']);
    Route::get('/inventory-events/unread/list', [InventoryEventController::class, 'getUnread']);
    Route::get('/inventory-events/stats/overview', [InventoryEventController::class, 'getStats']);
    Route::post('/inventory-events/{event}/process', [InventoryEventController::class, 'markAsProcessed']);
    Route::post('/inventory-events/{event}/notify', [InventoryEventController::class, 'markAsNotified']);
    
});

// Webhook for Stripe (public but secured by signature)
Route::post('/stripe/webhook', [AuthController::class, 'webhook'])->name('stripe.webhook');
