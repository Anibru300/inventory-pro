<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PurchaseOrder extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'supplier_id',
        'warehouse_id',
        'created_by',
        'order_number',
        'order_date',
        'expected_date',
        'received_date',
        'status',
        'notes',
        'terms',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'shipping_cost',
        'discount',
        'total',
        'reference',
        'currency',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_date' => 'date',
        'received_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_SENT = 'sent';
    const STATUS_PARTIAL = 'partial';
    const STATUS_RECEIVED = 'received';
    const STATUS_CANCELLED = 'cancelled';

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(PurchaseOrderStatusHistory::class)->orderBy('created_at', 'desc');
    }

    // Scopes
    public function scopeByTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', [self::STATUS_DRAFT, self::STATUS_SENT, self::STATUS_PARTIAL]);
    }

    // Helper methods
    public function calculateTotals(): void
    {
        $this->subtotal = $this->items->sum('subtotal');
        $this->tax_amount = $this->subtotal * ($this->tax_rate / 100);
        $this->total = $this->subtotal + $this->tax_amount + $this->shipping_cost - $this->discount;
        $this->save();
    }

    public function canEdit(): bool
    {
        return in_array($this->status, [self::STATUS_DRAFT]);
    }

    public function canReceive(): bool
    {
        return in_array($this->status, [self::STATUS_SENT, self::STATUS_PARTIAL]);
    }

    public function isFullyReceived(): bool
    {
        foreach ($this->items as $item) {
            if ($item->received_quantity < $item->quantity) {
                return false;
            }
        }
        return true;
    }

    public function updateReceivedQuantities(array $items): void
    {
        foreach ($items as $itemData) {
            $item = $this->items()->find($itemData['id']);
            if ($item) {
                $item->received_quantity += $itemData['quantity_received'];
                $item->save();
            }
        }

        // Update order status
        if ($this->isFullyReceived()) {
            $this->status = self::STATUS_RECEIVED;
            $this->received_date = now();
        } else {
            $this->status = self::STATUS_PARTIAL;
        }
        $this->save();
    }

    public function addStatusHistory(string $fromStatus, string $toStatus, ?string $notes = null): void
    {
        $this->statusHistory()->create([
            'user_id' => auth()->id(),
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'notes' => $notes,
        ]);
    }
}
