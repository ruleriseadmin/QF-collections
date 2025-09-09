<?php

namespace App\Models\Gateway;

use App\Models\Customer\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GatewayTempTransaction extends Model
{
    /** @use HasFactory<\Database\Factories\Gateway\GatewayTempTransactionFactory> */
    use HasFactory;

    protected $fillable = [
        'access_gateway_id',
        'customer_id',
        'reference',
        'provider',
        'meta',
        'status',
        'type',
        'amount',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    const PROVIDERS = [
        'paystack' => 'paystack',
    ];

    const TYPE = [
        'payment_link' => 'payment_link',
    ];

    public function accessGateway(): BelongsTo
    {
        return $this->belongsTo(AccessGateway::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
