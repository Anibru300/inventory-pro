<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseTransfer extends Model
{
    use HasFactory, HasUuids, SoftDeletes, BelongsToTenant;

    // Estados de transferencia
    const STATUS_PENDING = 'pending';
    const STATUS_PREPARING = 'preparing';
    const STATUS_IN_TRANSIT = 'in_transit';
    const STATUS_RECEIVED = 'received';
    const STATUS_PARTIALLY_RECEIVED = 'partially_received';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'tenant_id',
        'source_warehouse_id',
        'destination_warehouse_id',
        'transfer_number',
        'transfer_date',
        'expected_arrival_date',
        'actual_arrival_date',
        'status',
        'tracking_number',
        'carrier_name',
        'shipping_method',
        'total_items',
        'total_value',
        'shipping_cost',
        'created_by',
        'sent_by',
        'received_by',
        'sent_at',
        'received_at',
        'notes',
        'rejection_reason',
    ];

    protected $casts = [
        'transfer_date' => 'date',
        'expected_arrival_date' => 'date',
        'actual_arrival_date' => 'date',
        'total_items' => 'decimal:4',
        'total_value' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'sent_at' => 'datetime',
        'received_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => self::STATUS_PENDING,
        'total_items' => 0,
        'total_value' => 0,
    ];

    // Relaciones
    public function sourceWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'source_warehouse_id');
    }

    public function destinationWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'destination_warehouse_id');
    }

    public function items()
    {
        return $this->hasMany(WarehouseTransferItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeInTransit($query)
    {
        return $query->where('status', self::STATUS_IN_TRANSIT);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeBySourceWarehouse($query, $warehouseId)
    {
        return $query->where('source_warehouse_id', $warehouseId);
    }

    public function scopeByDestinationWarehouse($query, $warehouseId)
    {
        return $query->where('destination_warehouse_id', $warehouseId);
    }

    public function scopeInvolvingWarehouse($query, $warehouseId)
    {
        return $query->where(function ($q) use ($warehouseId) {
            $q->where('source_warehouse_id', $warehouseId)
              ->orWhere('destination_warehouse_id', $warehouseId);
        });
    }

    // Métodos de ayuda
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isInTransit(): bool
    {
        return $this->status === self::STATUS_IN_TRANSIT;
    }

    public function isReceived(): bool
    {
        return $this->status === self::STATUS_RECEIVED;
    }

    public function isCancelled(): bool
    {
        return in_array($this->status, [self::STATUS_CANCELLED, self::STATUS_REJECTED]);
    }

    public function canBeSent(): bool
    {
        return $this->status === self::STATUS_PENDING || $this->status === self::STATUS_PREPARING;
    }

    public function canBeReceived(): bool
    {
        return $this->status === self::STATUS_IN_TRANSIT || $this->status === self::STATUS_PARTIALLY_RECEIVED;
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_PREPARING]);
    }

    public function getProgressPercentageAttribute(): int
    {
        return match($this->status) {
            self::STATUS_PENDING => 0,
            self::STATUS_PREPARING => 25,
            self::STATUS_IN_TRANSIT => 50,
            self::STATUS_PARTIALLY_RECEIVED => 75,
            self::STATUS_RECEIVED => 100,
            self::STATUS_CANCELLED, self::STATUS_REJECTED => 0,
            default => 0,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        $labels = [
            self::STATUS_PENDING => 'Pendiente',
            self::STATUS_PREPARING => 'En Preparación',
            self::STATUS_IN_TRANSIT => 'En Tránsito',
            self::STATUS_RECEIVED => 'Recibida',
            self::STATUS_PARTIALLY_RECEIVED => 'Parcialmente Recibida',
            self::STATUS_CANCELLED => 'Cancelada',
            self::STATUS_REJECTED => 'Rechazada',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_PREPARING => 'blue',
            self::STATUS_IN_TRANSIT => 'indigo',
            self::STATUS_RECEIVED => 'green',
            self::STATUS_PARTIALLY_RECEIVED => 'orange',
            self::STATUS_CANCELLED => 'gray',
            self::STATUS_REJECTED => 'red',
            default => 'gray',
        };
    }

    public function calculateTotals(): void
    {
        $this->total_items = $this->items()->sum('quantity_requested');
        $this->total_value = $this->items()->sum('total_cost');
        $this->save();
    }
}
