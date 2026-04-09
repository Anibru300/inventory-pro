<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     * For API routes, return null to trigger a 401 JSON response.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Always return null for API routes - we want 401 JSON response, not redirect
        return null;
    }
    
    /**
     * Handle an unauthenticated user.
     * Override to return JSON for API requests.
     */
    protected function unauthenticated($request, array $guards)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            abort(response()->json([
                'message' => 'No autenticado. Por favor, inicia sesión.'
            ], 401));
        }
        
        parent::unauthenticated($request, $guards);
    }
}
