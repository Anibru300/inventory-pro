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
            $response = response('', 204);
            $this->addCorsHeaders($response);
            return $response;
        }
        
        $response = $next($request);
        $this->addCorsHeaders($response);
        
        return $response;
    }
    
    private function addCorsHeaders($response)
    {
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Auth-Token, X-Requested-With, X-Tenant-ID, Accept, Origin, X-XSRF-TOKEN');
        $response->headers->set('Access-Control-Expose-Headers', 'Authorization, X-Auth-Token');
        $response->headers->set('Access-Control-Max-Age', '86400');
    }
}
