<?php

namespace App\Models\Customer;

use App\Models\Gateway\AccessGateway;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerVirtualAccount extends Model
{
    /** @use HasFactory<\Database\Factories\Customer\CustomerVirtualAccountFactory> */
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'access_gateway_id',
        'provider',
        'account_number',
        'account_name',
        'bank_name',
        'bank_code',
        'status',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) str()->uuid();
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }


    public function accessGateway(): BelongsTo
    {
        return $this->belongsTo(AccessGateway::class, 'access_gateway_id', 'id');
    }
}
