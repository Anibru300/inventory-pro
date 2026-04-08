<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $requiredRole = UserRole::from($role);
        $userRole = UserRole::from($user->role);

        // Admin puede todo
        if ($userRole === UserRole::ADMIN) {
            return $next($request);
        }

        // Verificar jerarquía de roles
        $hierarchy = [
            UserRole::ADMIN->value => 4,
            UserRole::MANAGER->value => 3,
            UserRole::OPERATOR->value => 2,
            UserRole::VIEWER->value => 1,
        ];

        if ($hierarchy[$user->role] < $hierarchy[$role]) {
            return response()->json(['message' => 'Insufficient permissions'], 403);
        }

        return $next($request);
    }
}
