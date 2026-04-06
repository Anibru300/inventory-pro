<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Tenant extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'address',
        'tax_id',
        'logo_url',
        'plan',
        'status',
        'subscription_ends_at',
        'timezone',
        'currency',
        'language',
        'settings',
        'max_users',
        'max_products',
        'max_warehouses',
    ];

    protected $casts = [
        'settings' => 'array',
        'subscription_ends_at' => 'datetime',
        'max_users' => 'integer',
        'max_products' => 'integer',
        'max_warehouses' => 'integer',
    ];

    protected $attributes = [
        'plan' => 'starter',
        'status' => 'active',
        'timezone' => 'America/Mexico_City',
        'currency' => 'MXN',
        'language' => 'es',
        'max_users' => 1,
        'max_products' => 500,
        'max_warehouses' => 1,
    ];

    // Relaciones
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function warehouses()
    {
        return $this->hasMany(Warehouse::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSubscribed($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('subscription_ends_at')
              ->orWhere('subscription_ends_at', '>', now());
        });
    }

    // Métodos de ayuda
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isSubscribed(): bool
    {
        return $this->subscription_ends_at === null || 
               $this->subscription_ends_at->isFuture();
    }

    public function hasReachedLimit(string $resource): bool
    {
        $limit = $this->{"max_{$resource}"};
        
        if ($limit === null || $limit === 0) {
            return false;
        }

        $current = match($resource) {
            'users' => $this->users()->count(),
            'products' => $this->products()->count(),
            'warehouses' => $this->warehouses()->count(),
            default => 0,
        };

        return $current >= $limit;
    }
}