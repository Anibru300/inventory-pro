<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddCorsHeaders
{
    private array $allowedOrigins = [
        'https://inventory-pro-z81e.onrender.com',
        'http://localhost:5173',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $origin = $request->headers->get('Origin');
        
        // Handle preflight OPTIONS request
        if ($request->isMethod('OPTIONS')) {
            $response = response('', 200);
            $this->addCorsHeaders($response, $origin);
            return $response;
        }
        
        $response = $next($request);
        $this->addCorsHeaders($response, $origin);
        
        return $response;
    }
    
    private function addCorsHeaders(Response $response, ?string $origin): void
    {
        // Only allow specific origins when using credentials
        if ($origin && in_array($origin, $this->allowedOrigins, true)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
        } elseif (!$origin) {
            $response->headers->set('Access-Control-Allow-Origin', '*');
        }
        
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept, Origin, X-Tenant-ID');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        $response->headers->set('Access-Control-Expose-Headers', 'Authorization');
        $response->headers->set('Vary', 'Origin');
    }
}
