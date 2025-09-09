<?php

namespace App\Http\Requests\Webhook;

use App\Http\Requests\BaseRequest;

class UpdateWebhookUrlRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'webhookUrl' => ['required'],
        ];
    }
}
