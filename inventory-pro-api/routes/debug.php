<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Debug endpoint para verificar headers
Route::get('/debug/headers', function (Request $request) {
    return response()->json([
        'message' => 'Debug headers endpoint',
        'headers' => $request->headers->all(),
        'server' => [
            'HTTP_AUTHORIZATION' => $_SERVER['HTTP_AUTHORIZATION'] ?? 'NO SET',
            'HTTP_X_AUTH_TOKEN' => $_SERVER['HTTP_X_AUTH_TOKEN'] ?? 'NO SET',
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
