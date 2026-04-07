<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class InventoryEvent extends Model
{
    use HasFactory, HasUuids, BelongsToTenant;

    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_CRITICAL = 'critical';

    const EVENT_STOCK_LOW = 'stock_low';
    const EVENT_STOCK_OUT = 'stock_out';
    const EVENT_TRANSFER_COMPLETED = 'transfer_completed';
    const EVENT_LOT_EXPIRING = 'lot_expiring';
    const EVENT_LOT_EXPIRED = 'lot_expired';
    const EVENT_TRANSFER_IN_TRANSIT = 'transfer_in_transit';
    const EVENT_TRANSFER_RECEIVED = 'transfer_received';
    const EVENT_ADJUSTMENT_CREATED = 'adjustment_created';

    protected $fillable = [
        'tenant_id',
        'event_type',
        'entity_type',
        'entity_id',
        'payload',
        'metadata',
        'priority',
        'processed',
        'processed_at',
        'processing_error',
        'notified',
        'notified_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'metadata' => 'array',
        'processed' => 'boolean',
        'processed_at' => 'datetime',
        'notified' => 'boolean',
        'notified_at' => 'datetime',
    ];

    protected $attributes = [
        'priority' => self::PRIORITY_MEDIUM,
        'processed' => false,
        'notified' => false,
    ];

    // Scopes
    public function scopePending($query)
    {
        return $query->where('processed', false);
    }

    public function scopeProcessed($query)
    {
        return $query->where('processed', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('event_type', $type);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', [self::PRIORITY_HIGH, self::PRIORITY_CRITICAL]);
    }

    public function scopeByEntity($query, $type, $id)
    {
        return $query->where('entity_type', $type)->where('entity_id', $id);
    }

    // Métodos de ayuda
    public function markAsProcessed(): void
    {
        $this->processed = true;
        $this->processed_at = now();
        $this->save();
    }

    public function markAsNotified(): void
    {
        $this->notified = true;
        $this->notified_at = now();
        $this->save();
    }

    public function markAsFailed(string $error): void
    {
        $this->processing_error = $error;
        $this->save();
    }

    public function isProcessed(): bool
    {
        return $this->processed;
    }

    public function isNotified(): bool
    {
        return $this->notified;
    }

    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            self::PRIORITY_LOW => 'gray',
            self::PRIORITY_MEDIUM => 'blue',
            self::PRIORITY_HIGH => 'orange',
            self::PRIORITY_CRITICAL => 'red',
            default => 'gray',
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        $labels = [
            self::PRIORITY_LOW => 'Baja',
            self::PRIORITY_MEDIUM => 'Media',
            self::PRIORITY_HIGH => 'Alta',
            self::PRIORITY_CRITICAL => 'Crítica',
        ];

        return $labels[$this->priority] ?? $this->priority;
    }

    // Factory methods
    public static function createStockLowEvent($product, $warehouse, $currentStock, $minStock): self
    {
        return self::create([
            'tenant_id' => $product->tenant_id,
            'event_type' => self::EVENT_STOCK_LOW,
            'entity_type' => Product::class,
            'entity_id' => $product->id,
            'payload' => [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_sku' => $product->sku,
                'warehouse_id' => $warehouse->id,
                'warehouse_name' => $warehouse->name,
                'current_stock' => $currentStock,
                'min_stock' => $minStock,
            ],
            'priority' => $currentStock <= 0 ? self::PRIORITY_CRITICAL : self::PRIORITY_HIGH,
        ]);
    }

    public static function createLotExpiringEvent(ProductLot $lot, int $daysUntilExpiry): self
    {
        return self::create([
            'tenant_id' => $lot->tenant_id,
            'event_type' => self::EVENT_LOT_EXPIRING,
            'entity_type' => ProductLot::class,
            'entity_id' => $lot->id,
            'payload' => [
                'lot_id' => $lot->id,
                'lot_number' => $lot->lot_number,
                'product_id' => $lot->product_id,
                'product_name' => $lot->product->name ?? null,
                'warehouse_id' => $lot->warehouse_id,
                'warehouse_name' => $lot->warehouse->name ?? null,
                'expiry_date' => $lot->expiry_date?->toDateString(),
                'days_until_expiry' => $daysUntilExpiry,
                'remaining_quantity' => $lot->remaining_quantity,
            ],
            'priority' => $daysUntilExpiry <= 7 ? self::PRIORITY_HIGH : self::PRIORITY_MEDIUM,
        ]);
    }

    public static function createTransferEvent(WarehouseTransfer $transfer, string $eventType): self
    {
        return self::create([
            'tenant_id' => $transfer->tenant_id,
            'event_type' => $eventType,
            'entity_type' => WarehouseTransfer::class,
            'entity_id' => $transfer->id,
            'payload' => [
                'transfer_id' => $transfer->id,
                'transfer_number' => $transfer->transfer_number,
                'source_warehouse' => $transfer->sourceWarehouse->name ?? null,
                'destination_warehouse' => $transfer->destinationWarehouse->name ?? null,
                'status' => $transfer->status,
                'total_items' => $transfer->total_items,
                'total_value' => $transfer->total_value,
            ],
            'priority' => self::PRIORITY_MEDIUM,
        ]);
    }
}
