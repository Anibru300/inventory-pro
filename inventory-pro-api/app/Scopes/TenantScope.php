<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $tenantId = $this->getTenantId();
        
        if ($tenantId) {
            $builder->where($model->qualifyColumn('tenant_id'), $tenantId);
        }
    }

    /**
     * Get the current tenant ID
     */
    protected function getTenantId(): ?string
    {
        // Try authenticated user
        if (Auth::check() && Auth::user()->tenant_id) {
            return Auth::user()->tenant_id;
        }

        // Try request header
        if (request()->hasHeader('X-Tenant-ID')) {
            return request()->header('X-Tenant-ID');
        }

        // Try session
        return session('current_tenant_id');
    }
}