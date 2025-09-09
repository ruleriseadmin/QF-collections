<?php

namespace App\Models\Gateway;

use App\Models\Customer\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GatewayTransaction extends Model
{
    /** @use HasFactory<\Database\Factories\Gateway\GatewayTransactionFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'access_gateway_id',
        'customer_id',
        'reference',
        'provider',
        'provider_reference',
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
        'card_tokenization' => 'card_tokenization',
    ];

    const STATUS = [
        'pending' => 'pending',
        'success' => 'success',
        'failed' => 'failed',
    ];

    public function customer():BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
