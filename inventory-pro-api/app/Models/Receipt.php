<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receipt extends Model
{
    use HasFactory, HasUuids, SoftDeletes, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'stock_movement_id',
        'product_id',
        'warehouse_id',
        'folio',
        'type',
        'quantity',
        'unit_cost',
        'reference_number',
        'notes',
        'recipient_name',
        'recipient_department',
        'recipient_signature',
        'status',
        'printed_at',
        'created_by',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_cost' => 'decimal:2',
        'printed_at' => 'datetime',
    ];

    // Relationships
    public function stockMovement()
    {
        return $this->belongsTo(StockMovement::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Generate unique folio
    public static function generateFolio($type)
    {
        $prefix = $type === 'entry' ? 'ENT' : 'SAL';
        $year = date('Y');
        $count = self::whereYear('created_at', $year)
            ->where('type', $type)
            ->count() + 1;
        
        return sprintf('%s-%s-%05d', $prefix, $year, $count);
    }

    // Get total value
    public function getTotalAttribute()
    {
        return $this->unit_cost ? $this->quantity * $this->unit_cost : null;
    }
}