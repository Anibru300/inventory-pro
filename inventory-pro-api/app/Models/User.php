<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasUuids, Notifiable, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'email',
        'password',
        'first_name',
        'last_name',
        'phone',
        'avatar_url',
        'role',
        'permissions',
        'preferences',
        'is_active',
        'email_verified_at',
        'mfa_enabled',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'mfa_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'mfa_enabled' => 'boolean',
        'permissions' => 'array',
        'preferences' => 'array',
    ];

    protected $attributes = [
        'role' => 'user',
        'is_active' => true,
        'preferences' => '{"theme":"dark","language":"es","notifications_email":true,"notifications_push":true}',
    ];

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->preferences)) {
                $user->preferences = [
                    'theme' => 'dark',
                    'language' => 'es',
                    'notifications_email' => true,
                    'notifications_push' => true,
                ];
            }
        });
    }

    // Relaciones
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'created_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    // Métodos de ayuda
    public function fullName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    public function isManager(): bool
    {
        return $this->role === 'manager' || $this->isAdmin();
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        $permissions = $this->permissions ?? [];
        return in_array($permission, $permissions);
    }

    public function updateLastLogin()
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
        ]);
    }
}