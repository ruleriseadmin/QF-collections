<?php

namespace App\Models\Webhook;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookLog extends Model
{
    /** @use HasFactory<\Database\Factories\Webhook\WebhookLogFactory> */
    use HasFactory;

    protected $fillable = [
        'access_gateway_id',
        'type',
        'payload',
        'meta',
    ];

    protected $casts = [
        'payload' => 'array',
        'meta' => 'array',
    ];

    const TYPES = [
        'payment_link',
        'card_tokenization',
        'virtual_account',
    ];
}
