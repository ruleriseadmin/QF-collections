<?php

namespace App\Models\DirectDebit;

use Illuminate\Database\Eloquent\Model;

class TempDirectDebit extends Model
{
    protected $fillable = [
        'uuid',
        'access_gateway_id',
        'customer_id',
        'reference',
        'provider',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    const PROVIDERS = [
        'paystack' => 'paystack',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) str()->uuid();
        });
    }
}
