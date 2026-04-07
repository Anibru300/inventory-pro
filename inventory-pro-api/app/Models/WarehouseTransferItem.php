<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class WarehouseTransferItem extends Model
{
    use HasFactory, HasUuids, BelongsToTenant;

    const STATUS_PENDING = 'pending';
    const STATUS_SENT = 'sent';
    const STATUS_RECEIVED = 'received';
    const STATUS_PARTIALLY_RECEIVED = 'partially_received';
    const STATUS_DAMAGED = 'damaged';
    const STATUS_MISSING = 'missing';

    protected $fillable = [
        'tenant_id',
        'warehouse_transfer_id',
        'product_id',
        'product_lot_id',
        'quantity_requested',
        'quantity_sent',
        'quantity_received',
        'unit_cost',
        'total_cost',
        'status',
        'notes',
    ];

    protected $casts = [
        'quantity_requested' => 'decimal:4',
        'quantity_sent' => 'decimal:4',
        'quantity_received' => 'decimal:4',
        'unit_cost' => 'decimal:4',
        'total_cost' => 'decimal:2',
    ];

    protected $attributes = [
        'status' => self::STATUS_PENDING,
    ];

    // Relaciones
    public function transfer()
    {
        return $this->belongsTo(WarehouseTransfer::class, 'warehouse_transfer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productLot()
    {
        return $this->belongsTo(ProductLot::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeReceived($query)
    {
        return $query->where('status', self::STATUS_RECEIVED);
    }

    // Métodos de ayuda
    public function isFullyReceived(): bool
    {
        return $this->quantity_received >= $this->quantity_sent;
    }

    public function isPartiallyReceived(): bool
    {
        return $this->quantity_received > 0 && $this->quantity_received < $this->quantity_sent;
    }

    public function getPendingQuantityAttribute(): float
    {
        return ($this->quantity_sent ?? $this->quantity_requested) - ($this->quantity_received ?? 0);
    }

    public function getStatusLabelAttribute(): string
    {
        $labels = [
            self::STATUS_PENDING => 'Pendiente',
            self::STATUS_SENT => 'Enviado',
            self::STATUS_RECEIVED => 'Recibido',
            self::STATUS_PARTIALLY_RECEIVED => 'Parcialmente Recibido',
            self::STATUS_DAMAGED => 'Dañado',
            self::STATUS_MISSING => 'Faltante',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            if ($item->unit_cost && $item->quantity_requested) {
                $item->total_cost = $item->quantity_requested * $item->unit_cost;
            }
        });
    }
}
