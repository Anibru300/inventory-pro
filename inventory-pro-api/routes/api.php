<?php

use App\Http\Controllers\Api\AlertController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\GlobalSearchController;
use App\Http\Controllers\Api\InventoryEventController;
use App\Http\Controllers\Api\KardexController;
use App\Http\Controllers\Api\LabelController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductLotController;
use App\Http\Controllers\Api\StockMovementController;
use App\Http\Controllers\Api\StripeController;
use App\Http\Controllers\Api\WarehouseController;
use App\Http\Controllers\Api\WarehouseTransferController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
        'version' => '1.0.0',
    ]);
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
    Route::get('/inventory/general', [DashboardController::class, 'generalWarehouse']);
    
    // Products
    Route::apiResource('products', ProductController::class);
    Route::get('/products/low-stock', [ProductController::class, 'lowStock']);
    
    // Label Printing
    Route::get('/products/{product}/labels', [LabelController::class, 'generateProductLabel']);
    Route::post('/labels/batch', [LabelController::class, 'generateBatchLabels']);
    Route::get('/labels/templates', [LabelController::class, 'getLabelTemplates']);
    Route::get('/labels/preview', [LabelController::class, 'previewLabel']);
    
    // Categories
    Route::apiResource('categories', CategoryController::class);
    
    // Warehouses
    Route::apiResource('warehouses', WarehouseController::class);
    
    // Stock Movements
    Route::apiResource('stock-movements', StockMovementController::class);
    Route::get('/stock-movements/summary', [StockMovementController::class, 'summary']);
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
    
    // Global Search
    Route::get('/search', [GlobalSearchController::class, 'search']);
    Route::get('/search/commands', [GlobalSearchController::class, 'commands']);
    
    // Alerts
    Route::get('/alerts', [AlertController::class, 'index']);
    Route::get('/alerts/summary', [AlertController::class, 'summary']);
    Route::get('/alerts/reorder-suggestions', [AlertController::class, 'reorderSuggestions']);
    
    // Products Extended
    Route::get('/products-search/global', [ProductController::class, 'searchGlobal']);
    Route::get('/products-analysis/abc', [ProductController::class, 'abcAnalysis']);
    Route::post('/products-import/bulk', [ProductController::class, 'import']);
    Route::get('/products-import/template', [ProductController::class, 'downloadTemplate']);
    
});

// Webhook for Stripe (public but secured by signature)
Route::post('/stripe/webhook', [StripeController::class, 'webhook'])->name('stripe.webhook');

// Stripe public config
Route::get('/stripe/config', [StripeController::class, 'getConfig']);

// Stripe protected routes
Route::middleware('auth:sanctum')->prefix('payments')->group(function () {
    Route::post('/intent', [StripeController::class, 'createPaymentIntent']);
    Route::post('/subscribe', [StripeController::class, 'createSubscription']);
    Route::post('/cancel', [StripeController::class, 'cancelSubscription']);
    Route::post('/transfer', [StripeController::class, 'createTransferPayment']);
    Route::get('/history', [StripeController::class, 'getPaymentHistory']);
});
