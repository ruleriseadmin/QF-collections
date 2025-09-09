<?php

namespace App\Actions\Webhook;

use App\Jobs\Webhook\SendWebhookJob;
use App\Models\Gateway\AccessGateway;

class ProcessWebhookAction
{
    public function execute(AccessGateway $accessGateway, string $event, $payload)
    {
        $webhookConfig = $accessGateway->webhookConfig;

        if ( ! $webhookConfig->webhook_url ) return;

        $accessGateway->webhookLogs()->create([
            'type' => $event,
            'payload' => $payload,
        ]);

        $payload = [
            'event' => $event,
            'data' => $payload,
        ];

        SendWebhookJob::dispatch( $payload, $webhookConfig->webhook_url );
    }
}
