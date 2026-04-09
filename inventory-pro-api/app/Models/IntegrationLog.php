<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class IntegrationLog extends Model
{
    use HasUuids;

    protected $fillable = [
        'integration_id',
        'action',
        'status',
        'message',
        'details',
        'records_processed',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function integration()
    {
        return $this->belongsTo(Integration::class);
    }
}
