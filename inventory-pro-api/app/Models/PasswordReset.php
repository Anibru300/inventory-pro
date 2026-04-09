<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PasswordReset extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
        'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isValid(): bool
    {
        return $this->expires_at->isFuture() && is_null($this->used_at);
    }

    public function markAsUsed(): void
    {
        $this->update(['used_at' => now()]);
    }
}
