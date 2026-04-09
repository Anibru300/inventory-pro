<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PurchaseOrderItem extends Model
{
    use HasUuids;

    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'quantity',
        'received_quantity',
        'unit_cost',
        'subtotal',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'received_quantity' => 'decimal:3',
        'unit_cost' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relationships
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            $item->subtotal = $item->quantity * $item->unit_cost;
        });

        static::updating(function ($item) {
            $item->subtotal = $item->quantity * $item->unit_cost;
        });
    }

    // Helper methods
    public function remainingQuantity(): float
    {
        return $this->quantity - $this->received_quantity;
    }

    public function isFullyReceived(): bool
    {
        return $this->received_quantity >= $this->quantity;
    }
}
