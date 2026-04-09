<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Database\Seeders\TenantSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        $url = Socialite::driver('google')
            ->stateless()
            ->redirect()
            ->getTargetUrl();

        return response()->json(['url' => $url]);
    }

    /**
     * Handle Google callback
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Check if user exists
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // Update Google ID if not set
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }

                // Update avatar if available
                if ($googleUser->avatar && !$user->avatar_url) {
                    $user->update(['avatar_url' => $googleUser->avatar]);
                }

                $token = $user->createToken('auth-token')->plainTextToken;

                return response()->json([
                    'user' => $user->load('tenant'),
                    'token' => $token,
                    'is_new' => false,
                ]);
            }

            // Create new tenant and user
            return $this->createUserFromGoogle($googleUser);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al autenticar con Google: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Login/Register with Google token (for frontend JS SDK)
     */
    public function googleTokenLogin(Request $request)
    {
        $request->validate([
            'access_token' => 'required|string',
            'company_name' => 'required_if:is_new,true|string|max:255',
        ]);

        try {
            // Verify token with Google
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->userFromToken($request->access_token);

            if (!$googleUser) {
                return response()->json([
                    'message' => 'Token de Google inválido',
                ], 401);
            }

            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // Existing user - login
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }

                $token = $user->createToken('auth-token')->plainTextToken;

                return response()->json([
                    'user' => $user->load('tenant'),
                    'token' => $token,
                    'is_new' => false,
                ]);
            }

            // New user - need company name
            if (!$request->has('company_name')) {
                return response()->json([
                    'message' => 'Se requiere nombre de empresa para crear cuenta',
                    'requires_company' => true,
                    'email' => $googleUser->email,
                    'name' => $googleUser->name,
                ], 422);
            }

            // Create new user
            return $this->createUserFromGoogle($googleUser, $request->company_name);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create user from Google data
     */
    private function createUserFromGoogle($googleUser, $companyName = null)
    {
        // Generate company name if not provided
        $companyName = $companyName ?? $googleUser->name . ' Company';

        // Create tenant
        $tenant = Tenant::create([
            'name' => $companyName,
            'slug' => $this->generateSlug($companyName),
            'email' => $googleUser->email,
            'plan' => 'starter',
            'status' => 'active',
            'trial_ends_at' => now()->addDays(14),
        ]);

        // Parse name
        $nameParts = explode(' ', $googleUser->name, 2);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? '';

        // Create user
        $user = User::create([
            'tenant_id' => $tenant->id,
            'email' => $googleUser->email,
            'password' => Hash::make(Str::random(32)), // Random password
            'first_name' => $firstName,
            'last_name' => $lastName,
            'google_id' => $googleUser->id,
            'avatar_url' => $googleUser->avatar,
            'role' => 'admin',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        // Run tenant seeders
        $seeder = new TenantSeeder();
        $seeder->run($tenant->id);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'user' => $user->load('tenant'),
            'token' => $token,
            'is_new' => true,
            'message' => 'Cuenta creada exitosamente',
        ], 201);
    }

    /**
     * Link Google account to existing user
     */
    public function linkGoogleAccount(Request $request)
    {
        $request->validate([
            'access_token' => 'required|string',
        ]);

        $user = $request->user();

        try {
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->userFromToken($request->access_token);

            // Check if Google account is already linked to another user
            $existing = User::where('google_id', $googleUser->id)
                ->where('id', '!=', $user->id)
                ->first();

            if ($existing) {
                return response()->json([
                    'message' => 'Esta cuenta de Google ya está vinculada a otro usuario',
                ], 422);
            }

            $user->update([
                'google_id' => $googleUser->id,
                'avatar_url' => $googleUser->avatar ?? $user->avatar_url,
            ]);

            return response()->json([
                'message' => 'Cuenta de Google vinculada exitosamente',
                'user' => $user->load('tenant'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Unlink Google account
     */
    public function unlinkGoogleAccount(Request $request)
    {
        $user = $request->user();

        if (!$user->google_id) {
            return response()->json([
                'message' => 'No hay cuenta de Google vinculada',
            ], 422);
        }

        // Check if user has password set
        if (!$user->password || $user->password === '') {
            return response()->json([
                'message' => 'Debe establecer una contraseña antes de desvincular Google',
            ], 422);
        }

        $user->update(['google_id' => null]);

        return response()->json([
            'message' => 'Cuenta de Google desvinculada exitosamente',
        ]);
    }

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
