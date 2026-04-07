<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ProductLot extends Model
{
    use HasFactory, HasUuids, SoftDeletes, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'product_id',
        'warehouse_id',
        'lot_number',
        'initial_quantity',
        'remaining_quantity',
        'unit_cost',
        'manufacturing_date',
        'expiry_date',
        'received_date',
        'supplier_id',
        'purchase_order_number',
        'status',
        'notes',
    ];

    protected $casts = [
        'initial_quantity' => 'decimal:4',
        'remaining_quantity' => 'decimal:4',
        'unit_cost' => 'decimal:4',
        'manufacturing_date' => 'date',
        'expiry_date' => 'date',
        'received_date' => 'date',
    ];

    protected $attributes = [
        'status' => 'active',
    ];

    // Relaciones
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'active')
                     ->where('remaining_quantity', '>', 0);
    }

    public function scopeExpiring($query, $days = 30)
    {
        return $query->where('expiry_date', '<=', Carbon::now()->addDays($days))
                     ->where('expiry_date', '>=', Carbon::now())
                     ->where('status', 'active');
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', Carbon::now())
                     ->where('status', '!=', 'expired');
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByWarehouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    // Métodos de ayuda
    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function isExpiringSoon(int $days = 30): bool
    {
        return $this->expiry_date && 
               $this->expiry_date->diffInDays(Carbon::now()) <= $days &&
               $this->expiry_date->isFuture();
    }

    public function daysUntilExpiry(): ?int
    {
        if (!$this->expiry_date) return null;
        return $this->expiry_date->diffInDays(Carbon::now());
    }

    public function isDepleted(): bool
    {
        return $this->remaining_quantity <= 0;
    }

    public function getTotalValueAttribute(): float
    {
        return $this->remaining_quantity * $this->unit_cost;
    }

    public function getConsumptionPercentageAttribute(): float
    {
        if ($this->initial_quantity <= 0) return 0;
        return (($this->initial_quantity - $this->remaining_quantity) / $this->initial_quantity) * 100;
    }

    public function updateStatus(): void
    {
        if ($this->isExpired() && $this->status !== 'expired') {
            $this->status = 'expired';
            $this->save();
        } elseif ($this->isDepleted() && $this->status !== 'depleted') {
            $this->status = 'depleted';
            $this->save();
        }
    }

    // Boot para actualizar estado automáticamente
    protected static function boot()
    {
        parent::boot();
        
        static::retrieved(function ($lot) {
            $lot->updateStatus();
        });
    }
}
