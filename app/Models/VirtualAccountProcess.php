<?php

namespace App\Models;

use App\Models\Gateway\AccessGateway;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VirtualAccountProcess extends Model
{
    protected $fillable = [
        'customer_id',
        'access_gateway_id',
    ];

    public function accessGateway(): BelongsTo
    {
        return $this->belongsTo(AccessGateway::class);
    }
}
