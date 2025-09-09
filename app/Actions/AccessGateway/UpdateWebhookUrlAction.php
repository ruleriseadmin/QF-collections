<?php

namespace App\Actions\AccessGateway;

use App\Models\Gateway\AccessGateway;
use Exception;
use Illuminate\Support\Facades\Log;

class UpdateWebhookUrlAction
{
    public function execute(AccessGateway $accessGateway, string $webhookUrl): bool
    {
        try{
            $accessGateway->webhookConfig->update([
                'webhook_url' => $webhookUrl,
            ]);
            return true;
        }catch(Exception $ex){
            Log::error("Error @ UpdateWebhookAction: " . $ex->getMessage());
            return false;
        }
    }
}
