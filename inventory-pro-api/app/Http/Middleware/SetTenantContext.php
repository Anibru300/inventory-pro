<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetTenantContext
{
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el usuario está autenticado
        if (auth()->check()) {
            $user = auth()->user();
            $tenantId = $user->tenant_id;

            // Solo establecer contexto de RLS si usamos PostgreSQL
            if (DB::getDriverName() === 'pgsql') {
                DB::statement("SET app.current_tenant_id = ?", [$tenantId]);
            }
            
            // Almacenar en el request para uso posterior
            $request->attributes->set('tenant_id', $tenantId);
        }

        return $next($request);
    }
}
