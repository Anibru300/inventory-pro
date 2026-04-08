<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Tenant extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    // Planes disponibles
    const PLAN_FREE = 'free';
    const PLAN_PROFESSIONAL = 'professional';
    const PLAN_UNLIMITED = 'unlimited';

    // Precios en centavos (MXN)
    const PRICES = [
        self::PLAN_FREE => 0,
        self::PLAN_PROFESSIONAL => 29900, // $299.00
        self::PLAN_UNLIMITED => 79900,    // $799.00
    ];

    // Límites por plan
    const LIMITS = [
        self::PLAN_FREE => [
            'max_warehouses' => 1,
            'max_products' => 100,
            'max_users' => 1,
        ],
        self::PLAN_PROFESSIONAL => [
            'max_warehouses' => 10,
            'max_products' => 500,
            'max_users' => 5,
        ],
        self::PLAN_UNLIMITED => [
            'max_warehouses' => null, // ilimitado
            'max_products' => null,   // ilimitado
            'max_users' => null,      // ilimitado
        ],
    ];

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
        'trial_ends_at',
        'timezone',
        'currency',
        'language',
        'settings',
        'max_users',
        'max_products',
        'max_warehouses',
        'stripe_id',
        'stripe_subscription_id',
        'pm_type',
        'pm_last_four',
    ];

    protected $casts = [
        'settings' => 'array',
        'subscription_ends_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'max_users' => 'integer',
        'max_products' => 'integer',
        'max_warehouses' => 'integer',
    ];

    protected $attributes = [
        'plan' => self::PLAN_FREE,
        'status' => 'active',
        'timezone' => 'America/Mexico_City',
        'currency' => 'MXN',
        'language' => 'es',
        'max_users' => 1,
        'max_products' => 100,
        'max_warehouses' => 1,
    ];

    // Boot - aplicar límites según plan
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tenant) {
            $tenant->applyPlanLimits();
        });

        static::updating(function ($tenant) {
            if ($tenant->isDirty('plan')) {
                $tenant->applyPlanLimits();
            }
        });
    }

    // Aplicar límites del plan
    public function applyPlanLimits(): void
    {
        $limits = self::LIMITS[$this->plan] ?? self::LIMITS[self::PLAN_FREE];
        
        $this->max_warehouses = $limits['max_warehouses'];
        $this->max_products = $limits['max_products'];
        $this->max_users = $limits['max_users'];
    }

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

    public function payments()
    {
        return $this->hasMany(Payment::class);
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

    public function onTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function hasReachedLimit(string $resource): bool
    {
        $limit = $this->{"max_{$resource}"};
        
        // null o 0 = ilimitado
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

    public function getRemaining(string $resource): ?int
    {
        $limit = $this->{"max_{$resource}"};
        
        if ($limit === null || $limit === 0) {
            return null; // ilimitado
        }

        $current = match($resource) {
            'users' => $this->users()->count(),
            'products' => $this->products()->count(),
            'warehouses' => $this->warehouses()->count(),
            default => 0,
        };

        return max(0, $limit - $current);
    }

    public function isFree(): bool
    {
        return $this->plan === self::PLAN_FREE;
    }

    public function isProfessional(): bool
    {
        return $this->plan === self::PLAN_PROFESSIONAL;
    }

    public function isUnlimited(): bool
    {
        return $this->plan === self::PLAN_UNLIMITED;
    }

    public function getPlanPrice(): int
    {
        return self::PRICES[$this->plan] ?? 0;
    }

    public function canUpgrade(): bool
    {
        return in_array($this->plan, [self::PLAN_FREE, self::PLAN_PROFESSIONAL]);
    }

    public function getNextPlan(): ?string
    {
        return match($this->plan) {
            self::PLAN_FREE => self::PLAN_PROFESSIONAL,
            self::PLAN_PROFESSIONAL => self::PLAN_UNLIMITED,
            default => null,
        };
    }
}
