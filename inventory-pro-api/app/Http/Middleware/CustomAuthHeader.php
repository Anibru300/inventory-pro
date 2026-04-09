<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para convertir header X-Auth-Token a Authorization Bearer
 * 
 * Solución alternativa al problema de Apache 2.4 + mod_php que no pasa
 * el header Authorization a PHP correctamente.
 */
class CustomAuthHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Debug logging
        $customToken = $request->header('X-Auth-Token');
        $hasAuth = $request->hasHeader('Authorization');
        
        error_log("CustomAuthHeader Middleware:");
        error_log("  X-Auth-Token presente: " . ($customToken ? 'SÍ' : 'NO'));
        error_log("  Authorization presente: " . ($hasAuth ? 'SÍ' : 'NO'));
        
        // Si no hay header Authorization pero sí X-Auth-Token, convertirlo
        if ($customToken && !$request->bearerToken()) {
            $request->headers->set('Authorization', 'Bearer ' . $customToken);
            error_log("  -> Token convertido a Authorization Bearer");
        }
        
        return $next($request);
    }
}
