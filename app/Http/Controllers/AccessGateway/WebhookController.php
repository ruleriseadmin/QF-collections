<?php

namespace App\Http\Controllers\AccessGateway;

use App\Supports\ApiReturnResponse;
use App\Http\Controllers\BaseController;
use App\Actions\AccessGateway\UpdateWebhookUrlAction;
use App\Http\Requests\Webhook\UpdateWebhookUrlRequest;

class WebhookController extends BaseController
{
    public function updateWebhookUrl(UpdateWebhookUrlRequest $request)
    {
        return (new UpdateWebhookUrlAction)->execute($this->accessGateway, $request->input('webhookUrl'))
            ? ApiReturnResponse::success()
            : ApiReturnResponse::failed();
    }
}
