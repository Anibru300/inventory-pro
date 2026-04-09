<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PurchaseOrderStatusHistory extends Model
{
    use HasUuids;

    protected $fillable = [
        'purchase_order_id',
        'user_id',
        'from_status',
        'to_status',
        'notes',
    ];

    // Relationships
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
