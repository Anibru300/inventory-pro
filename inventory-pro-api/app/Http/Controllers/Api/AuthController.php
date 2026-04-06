<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new tenant and admin user
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',  // Full name from frontend
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Split full name into first and last name
        $nameParts = explode(' ', $validated['name'], 2);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? '';

        // Create tenant
        $tenant = Tenant::create([
            'name' => $validated['company_name'],
            'slug' => $this->generateSlug($validated['company_name']),
            'email' => $validated['email'],
            'plan' => 'starter',  // Default plan
            'status' => 'active',
            'trial_ends_at' => now()->addDays(14),
        ]);

        // Create admin user
        $user = User::create([
            'tenant_id' => $tenant->id,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create default warehouse
        $tenant->warehouses()->create([
            'name' => 'Almacén Principal',
            'code' => 'ALM-01',
            'is_primary' => true,
        ]);

        // Create token
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Tenant created successfully',
            'tenant' => $tenant,
            'user' => $user->load('tenant'),
            'token' => $token,
        ], 201);
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas.'],
            ]);
        }

        if (! $user->is_active) {
            return response()->json([
                'message' => 'Account is deactivated',
            ], 403);
        }

        if (! $user->tenant || $user->tenant->status !== 'active') {
            return response()->json([
                'message' => 'Tenant subscription is not active',
            ], 403);
        }

        $user->updateLastLogin();

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'user' => $user->load('tenant'),
            'token' => $token,
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * Get current user
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()->load('tenant'),
        ]);
    }

    /**
     * Generate unique slug
     */
    private function generateSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $counter = 1;

        while (Tenant::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}