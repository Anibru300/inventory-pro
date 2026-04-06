<?php

namespace App\Traits;

use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait BelongsToTenant
{
    /**
     * Boot the trait - add global scope and creating callback
     */
    protected static function bootBelongsToTenant()
    {
        // Add global scope to filter by tenant
        static::addGlobalScope(new TenantScope);

        // Automatically set tenant_id on create
        static::creating(function ($model) {
            if (empty($model->tenant_id)) {
                $model->tenant_id = static::getCurrentTenantId();
            }
        });
    }

    /**
     * Get the current tenant ID from session or auth
     */
    protected static function getCurrentTenantId(): ?string
    {
        // Try to get from authenticated user
        if (Auth::check() && Auth::user()->tenant_id) {
            return Auth::user()->tenant_id;
        }

        // Try to get from request header (for API keys)
        if (request()->hasHeader('X-Tenant-ID')) {
            return request()->header('X-Tenant-ID');
        }

        // Try to get from session
        return session('current_tenant_id');
    }

    /**
     * Relationship to tenant
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Tenant::class);
    }

    /**
     * Scope to filter by specific tenant
     */
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where($this->qualifyColumn('tenant_id'), $tenantId);
    }

    /**
     * Check if model belongs to a specific tenant
     */
    public function belongsToTenant($tenantId): bool
    {
        return $this->tenant_id === $tenantId;
    }
}