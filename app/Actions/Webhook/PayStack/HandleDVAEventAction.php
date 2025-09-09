<?php

namespace App\Actions\Webhook\PayStack;

use Exception;
use KodeDict\PHPUtil\PhpUtil;
use App\Enums\TransactionEnum;
use App\Models\Customer\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Gateway\AccessGateway;
use App\Models\VirtualAccountProcess;
use App\Actions\Webhook\ProcessWebhookAction;
use App\Models\Gateway\GatewayTransaction;

class HandleDVAEventAction
{
    private Customer $customer;

    private ?AccessGateway $accessGateway = null;

    private ?GatewayTransaction $transaction;

    public function execute(Customer $customer, $payload, string $event, array $meta = null)
    {
        $this->customer = $customer;

        DB::beginTransaction();
        try{
            match($event){
                'virtual_account_fund' => $this->handleOnDVAFund($payload, $meta['accessGateway']),
                default => $this->handleDefault($event, $payload),
            };
            DB::commit();
        }catch(Exception $ex){
            DB::rollBack();
            Log::error('Error @ HandleDVAEventAction: ' . $ex->getMessage());
            return;
        }

        if ( ! $this->accessGateway ) return;

        $this->sendWebhook($event, $payload, $this->accessGateway);
    }

    private function handleSuccessProcess($payload)
    {
        $this->customer->virtualAccounts()->create([
            'access_gateway_id' => $this->accessGateway->id,
            'provider' => TransactionEnum::PROVIDERS['paystack'],
            'account_number' => $payload['data']['dedicated_account']['account_number'],
            'account_name' => $payload['data']['dedicated_account']['account_name'],
            'bank_name' => $payload['data']['dedicated_account']['bank']['name'],
            'status' => 'active',
            'meta' => $payload,
        ]);
    }

    private function sendWebhook($event, $payload, $accessGateway)
    {
        $webhookPayload = collect([
            'customer' => [
                'email' => $this->customer->email,
            ],
        ]);

        $webhookData = match($event){
            'customer_identity_failed' => [
                'event' => 'customer_identity.failed',
                'payload' => $webhookPayload->merge([
                    'reason' => $payload['data']['reason']
                ]),
            ],
            'virtual_account_creation_failed' => [
                'event' => 'virtual_account.failed',
                'payload' => $webhookPayload->merge([
                    'reason' => 'Virtual could not be created',
                ]),
            ],
            'virtual_account_creation_success' => [
                'event' => 'virtual_account.success',
                'payload' => $webhookPayload->merge([
                    'accountName' => $payload['data']['dedicated_account']['account_name'],
                    'accountNumber' => $payload['data']['dedicated_account']['account_number'],
                    'currency' => $payload['data']['dedicated_account']['currency'],
                    'bankName' => $payload['data']['dedicated_account']['bank']['name'],
                ]),
            ],
            'virtual_account_fund' => [
                'event' => 'virtual_account.credit',
                'payload' => $webhookPayload->merge([
                    'amountInKobo' => $payload['data']['amount'],
                    'sender' => [
                        'accountNumber' => $payload['data']['authorization']['sender_bank_account_number'],
                        'accountName' => $payload['data']['authorization']['sender_name'],
                        'bankName' => $payload['data']['authorization']['sender_bank'],
                    ],
                    'customerAccountNumber' => $payload['data']['authorization']['receiver_bank_account_number'],
                    'transactionRef' => $this->transaction->reference,
                ]),
            ],
        };

        //trigger webhook
        (new ProcessWebhookAction)->execute(
            accessGateway: $accessGateway,
            event: $webhookData['event'],
            payload: $webhookData['payload']->toArray(),
        );
    }

    private function handleOnDVAFund($payload, $accessGateway)
    {
        $this->accessGateway = $accessGateway;

        $this->transaction = $accessGateway->transactions()->create([
            'customer_id' => $this->customer->id,
            'reference' => PhpUtil::generateUniqueReference('16', 'trx-'),
            'provider' => TransactionEnum::PROVIDERS['paystack'],
            'provider_reference' => $payload['data']['reference'],
            'meta' => $payload,
            'status' => TransactionEnum::STATUS['success'],
            'type' => TransactionEnum::TYPE['virtual_account_fund'],
            'amount' => $payload['data']['amount'],
        ]);
    }

    private function handleDefault($event, $payload)
    {
        $accountProcess = VirtualAccountProcess::where('customer_id', $this->customer->id)->first();

        if ( ! $accountProcess ) return;

        $this->accessGateway = $accountProcess->accessGateway;

        $event == 'virtual_account_creation_success' && $this->handleSuccessProcess($payload);

        //delete process
        $accountProcess->delete();
    }
}
