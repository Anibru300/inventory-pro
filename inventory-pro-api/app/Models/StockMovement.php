<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class StockMovement extends Model
{
    use HasFactory, HasUuids, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'product_id',
        'variant_id',
        'warehouse_id',
        'location_id',
        'movement_type',
        'quantity',
        'unit_cost',
        'reference_type',
        'reference_id',
        'reference_number',
        'serial_number',
        'lot_number',
        'expiry_date',
        'documents',
        'notes',
        'created_by',
        'running_balance',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_cost' => 'decimal:2',
        'expiry_date' => 'date',
        'documents' => 'array',
        'running_balance' => 'integer',
    ];

    // Tipos de movimiento
    const TYPE_PURCHASE = 'entrada_compra';
    const TYPE_RETURN_CUSTOMER = 'entrada_devolucion_cliente';
    const TYPE_ADJUSTMENT_POSITIVE = 'entrada_ajuste';
    const TYPE_TRANSFER_IN = 'entrada_transferencia';
    const TYPE_SALE = 'salida_venta';
    const TYPE_RETURN_SUPPLIER = 'salida_devolucion_proveedor';
    const TYPE_ADJUSTMENT_NEGATIVE = 'salida_ajuste';
    const TYPE_TRANSFER_OUT = 'salida_transferencia';
    const TYPE_WASTE = 'salida_merma';

    public static function getEntryTypes(): array
    {
        return [
            self::TYPE_PURCHASE,
            self::TYPE_RETURN_CUSTOMER,
            self::TYPE_ADJUSTMENT_POSITIVE,
            self::TYPE_TRANSFER_IN,
        ];
    }

    public static function getExitTypes(): array
    {
        return [
            self::TYPE_SALE,
            self::TYPE_RETURN_SUPPLIER,
            self::TYPE_ADJUSTMENT_NEGATIVE,
            self::TYPE_TRANSFER_OUT,
            self::TYPE_WASTE,
        ];
    }

    // Relaciones
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeEntries($query)
    {
        return $query->whereIn('movement_type', self::getEntryTypes());
    }

    public function scopeExits($query)
    {
        return $query->whereIn('movement_type', self::getExitTypes());
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByWarehouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    public function scopeByDateRange($query, $start, $end)
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }

    // Métodos de ayuda
    public function isEntry(): bool
    {
        return in_array($this->movement_type, self::getEntryTypes());
    }

    public function isExit(): bool
    {
        return in_array($this->movement_type, self::getExitTypes());
    }

    public function getTotalCostAttribute(): float
    {
        return $this->quantity * ($this->unit_cost ?? 0);
    }

    public function getMovementTypeLabelAttribute(): string
    {
        $labels = [
            self::TYPE_PURCHASE => 'Compra',
            self::TYPE_RETURN_CUSTOMER => 'Devolución de Cliente',
            self::TYPE_ADJUSTMENT_POSITIVE => 'Ajuste Positivo',
            self::TYPE_TRANSFER_IN => 'Transferencia Entrada',
            self::TYPE_SALE => 'Venta',
            self::TYPE_RETURN_SUPPLIER => 'Devolución a Proveedor',
            self::TYPE_ADJUSTMENT_NEGATIVE => 'Ajuste Negativo',
            self::TYPE_TRANSFER_OUT => 'Transferencia Salida',
            self::TYPE_WASTE => 'Merma',
        ];

        return $labels[$this->movement_type] ?? $this->movement_type;
    }
}