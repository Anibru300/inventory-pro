<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'Inventory Pro API',
        'version' => '1.0.0',
        'timestamp' => now()->toIso8601String(),
    ]);
});

// Health check endpoint (web)
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'Inventory Pro API',
        'version' => '1.0.0',
        'timestamp' => now()->toIso8601String(),
    ]);
});

// Ruta temporal para ver logs
Route::get('/logs', function () {
    $logFile = storage_path('logs/laravel.log');
    if (!file_exists($logFile)) {
        return 'Log file not found';
    }
    return nl2br(implode("", array_slice(file($logFile), -100)));
});
