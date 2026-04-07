<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class StockReservation extends Model
{
    use HasFactory, HasUuids, SoftDeletes, BelongsToTenant;

    const STATUS_ACTIVE = 'active';
    const STATUS_PARTIALLY_RELEASED = 'partially_released';
    const STATUS_FULLY_RELEASED = 'fully_released';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'tenant_id',
        'product_id',
        'warehouse_id',
        'product_lot_id',
        'quantity',
        'quantity_released',
        'quantity_consumed',
        'reservable_type',
        'reservable_id',
        'status',
        'expires_at',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'quantity_released' => 'decimal:4',
        'quantity_consumed' => 'decimal:4',
        'expires_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => self::STATUS_ACTIVE,
        'quantity_released' => 0,
        'quantity_consumed' => 0,
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

    public function productLot()
    {
        return $this->belongsTo(ProductLot::class);
    }

    public function reservable()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', Carbon::now());
        });
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', Carbon::now());
    }

    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeForWarehouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    // Métodos de ayuda
    public function getRemainingQuantityAttribute(): float
    {
        return $this->quantity - $this->quantity_released - $this->quantity_consumed;
    }

    public function isFullyReleased(): bool
    {
        return $this->remaining_quantity <= 0;
    }

    public function isPartiallyReleased(): bool
    {
        return $this->quantity_released > 0 && !$this->isFullyReleased();
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function release(float $amount): void
    {
        $this->quantity_released += $amount;
        $this->updateStatus();
        $this->save();
    }

    public function consume(float $amount): void
    {
        $this->quantity_consumed += $amount;
        $this->updateStatus();
        $this->save();
    }

    public function cancel(): void
    {
        $this->status = self::STATUS_CANCELLED;
        $this->save();
    }

    public function expire(): void
    {
        if ($this->isExpired() && $this->status === self::STATUS_ACTIVE) {
            $this->status = self::STATUS_EXPIRED;
            $this->save();
        }
    }

    protected function updateStatus(): void
    {
        if ($this->isFullyReleased()) {
            $this->status = self::STATUS_FULLY_RELEASED;
        } elseif ($this->quantity_released > 0 || $this->quantity_consumed > 0) {
            $this->status = self::STATUS_PARTIALLY_RELEASED;
        }
    }

    public function getStatusLabelAttribute(): string
    {
        $labels = [
            self::STATUS_ACTIVE => 'Activa',
            self::STATUS_PARTIALLY_RELEASED => 'Parcialmente Liberada',
            self::STATUS_FULLY_RELEASED => 'Totalmente Liberada',
            self::STATUS_EXPIRED => 'Expirada',
            self::STATUS_CANCELLED => 'Cancelada',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'green',
            self::STATUS_PARTIALLY_RELEASED => 'orange',
            self::STATUS_FULLY_RELEASED => 'gray',
            self::STATUS_EXPIRED => 'red',
            self::STATUS_CANCELLED => 'red',
            default => 'gray',
        };
    }

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($reservation) {
            if ($reservation->remaining_quantity < 0) {
                throw new \InvalidArgumentException('La cantidad liberada/consumida no puede exceder la cantidad reservada');
            }
        });
    }
}
