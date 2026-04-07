<?php

use Illuminate\Support\Facades\Route;

// Health check - raíz
Route::get('/', function () {
    return 'Inventory Pro API v3 - OK';
});

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'Inventory Pro API',
        'version' => '1.0.0',
    ]);
});

// Ruta para ver logs
Route::get('/logs', function () {
    $logFile = storage_path('logs/laravel.log');
    if (!file_exists($logFile)) {
        return 'Log file not found';
    }
    return nl2br(implode("", array_slice(file($logFile), -100)));
});
