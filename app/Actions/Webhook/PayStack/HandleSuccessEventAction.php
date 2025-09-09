<?php

namespace App\Actions\Webhook\PayStack;

use App\Actions\Webhook\ProcessWebhookAction;
use App\Traits\CustomerTrait;
use KodeDict\PHPUtil\PhpUtil;
use App\Enums\TransactionEnum;
use App\Models\Gateway\AccessGateway;
use App\Models\Gateway\GatewayTempTransaction;
use App\Traits\TransactionResourceTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HandleSuccessEventAction
{
   use CustomerTrait, TransactionResourceTrait;

   private AccessGateway $accessGateway;

   private GatewayTempTransaction $tempTransaction;

    public function execute(GatewayTempTransaction $tempTransaction, $payload)
    {
        $accessGateway = $tempTransaction->accessGateway;

        $transaction = $accessGateway->transactions()
            ->where('reference', $tempTransaction->reference)
            ->orWhere('provider_reference', $tempTransaction->reference)->first();

        $this->tempTransaction = $tempTransaction;

        $this->accessGateway = $accessGateway;

        $event = "{$tempTransaction->type}.success";

        DB::beginTransaction();
        try{
            if ( ! $transaction ){
                $tempTransaction->type == TransactionEnum::TYPE['card_tokenization'] && $this->handleCardTokenization($payload);

                $accessGateway->transactions()->create([
                    'reference' => PhpUtil::generateUniqueReference('16', 'trx-'),
                    'provider_reference' => $tempTransaction->reference,
                    'provider' => $tempTransaction->provider,
                    'amount' => $tempTransaction->amount,
                    'status' => TransactionEnum::STATUS['success'],
                    'customer_id' => $tempTransaction->customer_id,
                    'type' => $tempTransaction->type,
                    'meta' => $payload,
                ]);

                $tempTransaction->delete();
            }
            DB::commit();
        }catch(Exception $ex){
            DB::rollBack();
            Log::error("Error @ HandleSuccessEventAction: " . $ex->getMessage());
            return;
        }

        //trigger webhook
        (new ProcessWebhookAction)->execute(
            accessGateway: $accessGateway,
            event: $event,
            payload: match($tempTransaction->type){
                TransactionEnum::TYPE['direct_debit'] => collect($this->defaultPayload($payload))->merge($this->directDebitResponse($payload))->toArray(),
                TransactionEnum::TYPE['card_tokenization'] => collect($this->defaultPayload($payload))->merge($this->cardTokenizationResponse($payload))->toArray(),
                default => $this->defaultPayload($payload),
            },
        );
    }

    public function handleCardTokenization($payload)
    {
        $authorization = $payload['data']['authorization'];

        $this->accessGateway->cardTokens()->create([
            'customer_id' => $this->tempTransaction->customer->id,
            'provider' => $this->tempTransaction->provider,
            'auth_token' => $authorization['authorization_code'],
            'meta' => $authorization,
            //'active' => true,
            'expiry_month'  => $authorization['exp_month'],
            'expiry_year' => $authorization['exp_year'],
        ]);
    }

    public function defaultPayload($payload): array
    {
        $amount = match($payload){
            collect($payload)->has('data.amount') => $payload['data']['amount'],
            collect($payload)->has('request.amount') => $payload['request']['amount'],
            default => $this->tempTransaction->amount,
        };

        return [
            'reference' => $this->tempTransaction->reference,
            'amount' => $amount,
            'customer' => $this->tempTransaction->customer ? [
                'email' => $this->tempTransaction->customer->email,
            ] : null,
        ];
    }

    public function payloadWithAuthorization($payload): array
    {
        $payload = $payload['data']['authorization'] ?? $payload['data'];

        collect($this->defaultPayload($payload))->merge($payload);

        return [
            'reference' => $this->tempTransaction->reference,
            'authorizationCode' => $payload['authorization_code'],
            'reusable' => $payload['reusable'],
            'expYear' => $payload['exp_year'],
            'expMonth' => $payload['exp_month'],
            'last4' => $payload['last4'],
            'active' => $payload['active'] ?? true,
            'customer' => $this->tempTransaction->customer ? [
                'email' => $this->tempTransaction->customer->email,
            ] : null,
        ];
    }
}
