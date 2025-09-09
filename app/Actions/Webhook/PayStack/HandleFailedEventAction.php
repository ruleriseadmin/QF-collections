<?php

namespace App\Actions\Webhook\PayStack;

use App\Actions\Webhook\ProcessWebhookAction;
use App\Traits\CustomerTrait;
use KodeDict\PHPUtil\PhpUtil;
use App\Enums\TransactionEnum;
use App\Models\Gateway\AccessGateway;
use App\Models\Gateway\GatewayTempTransaction;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HandleFailedEventAction
{
   use CustomerTrait;

   private AccessGateway $accessGateway;

   private GatewayTempTransaction $tempTransaction;

    public function execute(GatewayTempTransaction $tempTransaction, $payload, string $event = null, string $status = null)
    {
        $accessGateway = $tempTransaction->accessGateway;

        $transaction = $accessGateway->transactions()
            ->where('reference', $tempTransaction->reference)
            ->orWhere('provider_reference', $tempTransaction->reference)->first();

        $this->tempTransaction = $tempTransaction;

        $this->accessGateway = $accessGateway;

        $event = $event ?? "{$tempTransaction->type}.failed";

        DB::beginTransaction();
        try{
            if ( ! $transaction ){
                $accessGateway->transactions()->create([
                    'reference' => PhpUtil::generateUniqueReference('16', 'trx-'),
                    'provider_reference' => $tempTransaction->reference,
                    'provider' => $tempTransaction->provider,
                    'amount' => $tempTransaction->amount,
                    'status' => $status ?? TransactionEnum::STATUS['failed'],
                    'customer_id' => $tempTransaction->customer_id,
                    'type' => $tempTransaction->type,
                    'meta' => $payload,
                ]);

                $tempTransaction->delete();
            }
            DB::commit();
        }catch(Exception $ex){
            DB::rollBack();
            Log::error("Error @ HandleFailedEventAction: " . $ex->getMessage());
            return;
        }

        //trigger webhook
        (new ProcessWebhookAction)->execute(
            accessGateway: $accessGateway,
            event: $event,
            payload: [
                'reference' => $tempTransaction->reference,
                'amount' => $tempTransaction->amount,
                'customer' => $tempTransaction->customer ? [
                    'email' => $tempTransaction->customer->email,
                ] : null,
            ],
        );
    }
}
