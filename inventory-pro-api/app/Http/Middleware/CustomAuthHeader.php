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
        // Si no hay header Authorization pero sí X-Auth-Token, convertirlo
        $customToken = $request->header('X-Auth-Token');
        
        if ($customToken && !$request->bearerToken()) {
            $request->headers->set('Authorization', 'Bearer ' . $customToken);
        }
        
        return $next($request);
    }
}
