<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Payment extends Model
{
    use HasFactory, HasUuids;

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_REFUNDED = 'refunded';

    const METHOD_CARD = 'card';
    const METHOD_TRANSFER = 'transfer';
    const METHOD_CASH = 'cash';

    protected $fillable = [
        'tenant_id',
        'user_id',
        'amount',
        'currency',
        'plan',
        'period_start',
        'period_end',
        'status',
        'method',
        'stripe_payment_intent_id',
        'stripe_subscription_id',
        'stripe_invoice_id',
        'receipt_url',
        'metadata',
        'notes',
    ];

    protected $casts = [
        'amount' => 'integer',
        'period_start' => 'datetime',
        'period_end' => 'datetime',
        'metadata' => 'array',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function getAmountFormatted(): string
    {
        return '$' . number_format($this->amount / 100, 2) . ' ' . $this->currency;
    }
}
