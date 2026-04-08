<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Admin tiene todos los permisos
        if ($user->role === UserRole::ADMIN->value) {
            return $next($request);
        }

        // Verificar permisos específicos
        $role = UserRole::from($user->role);
        $permissions = $role->permissions();

        if (!in_array($permission, $permissions)) {
            return response()->json(['message' => 'Permission denied: ' . $permission], 403);
        }

        return $next($request);
    }
}
