<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/api/health');
});

// Ruta temporal para ver logs
Route::get('/logs', function () {
    $logFile = storage_path('logs/laravel.log');
    if (!file_exists($logFile)) {
        return 'Log file not found';
    }
    return nl2br(implode("", array_slice(file($logFile), -100)));
});
