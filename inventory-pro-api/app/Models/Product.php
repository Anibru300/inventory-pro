<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, HasUuids, SoftDeletes, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'category_id',
        'sku',
        'barcode',
        'name',
        'description',
        'attributes',
        'unit_of_measure',
        'weight',
        'dimensions',
        'stock_min',
        'stock_max',
        'reorder_point',
        'unit_cost',
        'selling_price',
        'images',
        'is_active',
        'is_serialized',
        'is_lotted',
        'valuation_method',
        'preferred_supplier_id',
    ];

    protected $casts = [
        'attributes' => 'array',
        'dimensions' => 'array',
        'images' => 'array',
        'unit_cost' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'weight' => 'decimal:3',
        'stock_min' => 'integer',
        'stock_max' => 'integer',
        'reorder_point' => 'integer',
        'is_active' => 'boolean',
        'is_serialized' => 'boolean',
        'is_lotted' => 'boolean',
    ];

    protected $attributes = [
        'unit_of_measure' => 'piece',
        'stock_min' => 0,
        'reorder_point' => 0,
        'unit_cost' => 0,
        'selling_price' => 0,
        'is_active' => true,
        'is_serialized' => false,
        'is_lotted' => false,
    ];

    // Relaciones
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stockLevels()
    {
        return $this->hasMany(StockLevel::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function priceHistory()
    {
        return $this->hasMany(ProductPriceHistory::class)->orderBy('created_at', 'desc');
    }

    public function preferredSupplier()
    {
        return $this->belongsTo(Supplier::class, 'preferred_supplier_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereHas('stockLevels', function ($q) {
            $q->whereRaw('quantity <= products.stock_min')
              ->where('quantity', '>', 0);
        });
    }

    public function scopeOutOfStock($query)
    {
        return $query->whereHas('stockLevels', function ($q) {
            $q->where('quantity', '<=', 0);
        })->orWhereDoesntHave('stockLevels');
    }

    // Métodos de ayuda
    public function getTotalStockAttribute(): int
    {
        return $this->stockLevels()->sum('quantity');
    }

    public function getAvailableStockAttribute(): int
    {
        return $this->stockLevels()->sum('available_quantity');
    }

    public function isLowStock(): bool
    {
        $total = $this->total_stock;
        return $total > 0 && $total <= $this->stock_min;
    }

    public function isOutOfStock(): bool
    {
        return $this->total_stock <= 0;
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->isOutOfStock()) {
            return 'out_of_stock';
        }
        if ($this->isLowStock()) {
            return 'low_stock';
        }
        return 'ok';
    }

    public function getPrimaryImageAttribute(): ?string
    {
        if (!empty($this->images) && is_array($this->images)) {
            $primary = collect($this->images)->firstWhere('is_primary', true);
            return $primary['url'] ?? $this->images[0]['url'] ?? null;
        }
        return null;
    }
}