<?php

namespace App\Models\Webhook;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebhookConfig extends Model
{
    /** @use HasFactory<\Database\Factories\Webhook\WebhookConfigFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'access_gateway_id',
        'webhook_url',
        'callback_url',
    ];
}
