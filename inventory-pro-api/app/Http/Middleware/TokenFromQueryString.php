<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TokenFromQueryString
{
    /**
     * Handle an incoming request.
     * If no Authorization header is present, check for token in query string.
     */
    public function handle(Request $request, Closure $next)
    {
        // If no Authorization header but token in query string, use it
        if (!$request->header('Authorization') && $request->has('api_token')) {
            $token = $request->input('api_token');
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }
        
        return $next($request);
    }
}
