<?php

use Illuminate\Support\Facades\Route;

// Root - simple text response
Route::get('/', function () {
    return response('OK - Inventory Pro API v3', 200)
        ->header('Content-Type', 'text/plain');
});

// Health check
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

// Logs
Route::get('/logs', function () {
    $logFile = storage_path('logs/laravel.log');
    if (!file_exists($logFile)) return 'No logs';
    return nl2br(implode("", array_slice(file($logFile), -50)));
});
