<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\StockMovementController;
use App\Http\Controllers\Api\WarehouseController;
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
    
});

// Webhook for Stripe (public but secured by signature)
Route::post('/stripe/webhook', [AuthController::class, 'webhook'])->name('stripe.webhook');