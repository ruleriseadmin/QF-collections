<?php

namespace App\Models\DirectDebit;

use Illuminate\Database\Eloquent\Model;

class RevokedMandate extends Model
{
    protected $fillable = [
        'mandate_code',
        'customer_code',
        'email',
        'status',
        'revoked_at',
        'meta',
    ];

    protected $casts = [
        'revoked_at' => 'datetime',
        'meta' => 'array',
    ];
}
