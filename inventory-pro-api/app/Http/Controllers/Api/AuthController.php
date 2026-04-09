<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use App\Mail\VerifyEmailMail;
use App\Models\PasswordReset;
use App\Models\Tenant;
use App\Models\User;
use Database\Seeders\TenantSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'company_name' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $nameParts = explode(' ', $validated['name'], 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';

            // Create tenant
            $tenant = Tenant::create([
                'name' => $validated['company_name'],
                'slug' => $this->generateSlug($validated['company_name']),
                'email' => $validated['email'],
                'plan' => 'starter',
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
                'is_active' => true,
            ]);

            // Run tenant seeders (creates default warehouse and categories)
            $seeder = new TenantSeeder();
            $seeder->run($tenant->id);

            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'message' => 'Tenant created successfully',
                'tenant' => $tenant,
                'user' => $user->load('tenant'),
                'token' => $token,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registration failed: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Las credenciales son incorrectas.',
                'errors' => ['email' => ['Las credenciales son incorrectas.']]
            ], 422);
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

        // Update last login
        $user->update([
            'last_login_at' => now(),
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'user' => $user->load('tenant'),
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()->load('tenant'),
        ]);
    }

    public function webhook(Request $request)
    {
        return response()->json(['message' => 'Webhook received']);
    }

    /**
     * Solicitar recuperación de contraseña
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        // Invalidar tokens anteriores
        PasswordReset::where('user_id', $user->id)
            ->whereNull('used_at')
            ->update(['used_at' => now()]);

        // Crear nuevo token
        $token = Str::random(64);
        
        $reset = PasswordReset::create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => now()->addHours(24),
        ]);

        // Enviar email
        $resetUrl = config('app.frontend_url', 'https://inventory-pro-z81e.onrender.com') . '/#/reset-password?token=' . $token;
        
        try {
            Mail::to($user->email)->send(new PasswordResetMail($user, $resetUrl));
        } catch (\Exception $e) {
            \Log::error('Error sending password reset email: ' . $e->getMessage());
            // Continuamos para no revelar si el email existe
        }

        return response()->json([
            'message' => 'Si el correo existe en nuestro sistema, recibirás instrucciones para recuperar tu contraseña.',
        ]);
    }

    /**
     * Validar token de recuperación
     */
    public function validateResetToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $reset = PasswordReset::where('token', $request->token)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();

        if (! $reset) {
            return response()->json([
                'valid' => false,
                'message' => 'El enlace ha expirado o no es válido.',
            ], 400);
        }

        return response()->json([
            'valid' => true,
            'email' => $reset->user->email,
        ]);
    }

    /**
     * Restablecer contraseña
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $reset = PasswordReset::where('token', $request->token)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();

        if (! $reset) {
            return response()->json([
                'message' => 'El enlace ha expirado o no es válido.',
            ], 400);
        }

        $user = $reset->user;
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        $reset->markAsUsed();

        // Invalidar todos los tokens de acceso
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Tu contraseña ha sido restablecida exitosamente.',
        ]);
    }

    /**
     * Reenviar verificación de email
     */
    public function resendVerification(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->email_verified_at) {
            return response()->json([
                'message' => 'El correo ya ha sido verificado.',
            ]);
        }

        // Generar token de verificación
        $token = Str::random(64);
        
        // Guardar token en el usuario (podría ser una tabla separada)
        $user->update([
            'verification_token' => $token,
            'verification_token_expires_at' => now()->addHours(24),
        ]);

        // Enviar email
        $verifyUrl = config('app.frontend_url', 'https://inventory-pro-z81e.onrender.com') . '/#/verify-email?token=' . $token;
        
        try {
            Mail::to($user->email)->send(new VerifyEmailMail($user, $verifyUrl));
        } catch (\Exception $e) {
            \Log::error('Error sending verification email: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al enviar el correo. Intenta más tarde.',
            ], 500);
        }

        return response()->json([
            'message' => 'Se ha enviado un correo de verificación.',
        ]);
    }

    /**
     * Verificar email
     */
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $user = User::where('verification_token', $request->token)
            ->where('verification_token_expires_at', '>', now())
            ->first();

        if (! $user) {
            return response()->json([
                'message' => 'El enlace ha expirado o no es válido.',
            ], 400);
        }

        $user->update([
            'email_verified_at' => now(),
            'verification_token' => null,
            'verification_token_expires_at' => null,
        ]);

        return response()->json([
            'message' => 'Correo verificado exitosamente.',
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
