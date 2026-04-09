<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TokenFromQueryString
{
    /**
     * Handle an incoming request.
     * If no Authorization header is present, check for token in query string.
     */
    public function handle(Request $request, Closure $next)
    {
        \Log::info('TokenFromQueryString middleware executed');
        \Log::info('Has Authorization header: ' . ($request->header('Authorization') ? 'YES' : 'NO'));
        \Log::info('Has api_token in query: ' . ($request->has('api_token') ? 'YES' : 'NO'));
        
        // If no Authorization header but token in query string, use it
        if (!$request->header('Authorization') && $request->has('api_token')) {
            $token = $request->input('api_token');
            \Log::info('Setting Authorization from query string');
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }
        
        \Log::info('Final Authorization header: ' . ($request->header('Authorization') ? 'Present' : 'Not present'));
        
        return $next($request);
    }
}
