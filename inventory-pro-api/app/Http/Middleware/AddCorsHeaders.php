<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddCorsHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $headers = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, X-Tenant-ID, Accept, Origin',
            'Access-Control-Expose-Headers' => 'Authorization, X-Auth-Token',
            'Access-Control-Max-Age' => '86400',
        ];

        // Handle preflight OPTIONS request
        if ($request->isMethod('OPTIONS')) {
            $response = response('', 200);
            foreach ($headers as $key => $value) {
                $response->headers->set($key, $value);
            }
            return $response;
        }
        
        $response = $next($request);
        
        // Add CORS headers to all responses
        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }
        
        return $response;
    }
}
