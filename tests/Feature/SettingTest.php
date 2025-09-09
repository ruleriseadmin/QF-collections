<?php

use App\Actions\AccessGateway\GenerateAccessGatewayAction;
use App\Models\Webhook\WebhookConfig;

describe('SettingTest', function () {
   it('That webhook url is updated', function () {
       $token = (new GenerateAccessGatewayAction)->execute();

       $response = (object) $this->withHeaders([
        'Authorization' => 'Bearer ' . $token['token']
        ])->post('/settings/update-webhook-url', [
            'webhookUrl' => 'https://www.google.com/',
        ])->json();

        $webhook = WebhookConfig::first();

        expect($webhook->webhook_url)->toBe('https://www.google.com/');
    });
});

