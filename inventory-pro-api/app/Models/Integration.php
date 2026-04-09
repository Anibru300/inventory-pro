<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\Crypt;

class Integration extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'type',
        'name',
        'status',
        'credentials',
        'settings',
        'sync_products',
        'sync_stock',
        'sync_orders',
        'last_sync_at',
        'last_sync_error',
    ];

    protected $casts = [
        'credentials' => 'encrypted:array',
        'settings' => 'array',
        'sync_products' => 'boolean',
        'sync_stock' => 'boolean',
        'sync_orders' => 'boolean',
        'last_sync_at' => 'datetime',
    ];

    // Types
    const TYPE_SHOPIFY = 'shopify';
    const TYPE_WOOCOMMERCE = 'woocommerce';
    const TYPE_MERCADOLIBRE = 'mercadolibre';

    // Status
    const STATUS_ACTIVE = 'active';
    const STATUS_PAUSED = 'paused';
    const STATUS_ERROR = 'error';

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function logs()
    {
        return $this->hasMany(IntegrationLog::class)->orderBy('created_at', 'desc');
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function isShopify(): bool
    {
        return $this->type === self::TYPE_SHOPIFY;
    }

    public function isWooCommerce(): bool
    {
        return $this->type === self::TYPE_WOOCOMMERCE;
    }

    public function getShopDomain(): ?string
    {
        return $this->credentials['shop_domain'] ?? null;
    }

    public function getAccessToken(): ?string
    {
        return $this->credentials['access_token'] ?? null;
    }

    public function log(string $action, string $status, ?string $message = null, array $details = [], int $recordsProcessed = 0): void
    {
        $this->logs()->create([
            'action' => $action,
            'status' => $status,
            'message' => $message,
            'details' => $details,
            'records_processed' => $recordsProcessed,
        ]);
    }
}
