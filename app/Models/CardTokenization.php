<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CardTokenization extends Model
{
    use SoftDeletes;

     protected $fillable = [
        'access_gateway_id',
        'customer_id',
        'provider',
        'auth_token',
        'meta',
        'active',
        'expiry_month',
        'expiry_year',
    ];

    protected $casts = [
        'meta' => 'array',
    ];
}
