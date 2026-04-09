<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProductPriceHistory extends Model
{
    use HasUuids;

    protected $table = 'product_price_history';

    protected $fillable = [
        'tenant_id',
        'product_id',
        'changed_by',
        'old_cost',
        'new_cost',
        'old_price',
        'new_price',
        'reason',
    ];

    protected $casts = [
        'old_cost' => 'decimal:2',
        'new_cost' => 'decimal:2',
        'old_price' => 'decimal:2',
        'new_price' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
