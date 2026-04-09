<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddCorsHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        // Handle preflight OPTIONS request
        if ($request->isMethod('OPTIONS')) {
            $response = response('', 200);
            $this->addCorsHeaders($response);
            return $response;
        }
        
        $response = $next($request);
        
        // Add CORS headers only if not already present
        $this->addCorsHeaders($response);
        
        return $response;
    }
    
    private function addCorsHeaders(Response $response): void
    {
        // Only set if not already present to avoid duplicates
        if (!$response->headers->has('Access-Control-Allow-Origin')) {
            $response->headers->set('Access-Control-Allow-Origin', '*');
        }
        if (!$response->headers->has('Access-Control-Allow-Methods')) {
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        }
        if (!$response->headers->has('Access-Control-Allow-Headers')) {
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-Tenant-ID, Accept, Origin');
        }
        if (!$response->headers->has('Access-Control-Expose-Headers')) {
            $response->headers->set('Access-Control-Expose-Headers', 'Authorization');
        }
    }
}
