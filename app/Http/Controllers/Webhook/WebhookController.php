<?php

namespace App\Http\Controllers\Webhook;

use App\Enums\TransactionEnum;
use App\Models\Customer\Customer;
use App\Models\Gateway\GatewayTempTransaction;
use App\Jobs\Webhook\PayStack\HandleWebhookJob;
use App\Http\Middleware\Webhook\PayStackVerifyWebhookSignature;
use App\Jobs\Webhook\PayStack\HandleDVACreditWebhook;
use App\Models\Gateway\GatewayTransaction;
use Digikraaft\PaystackWebhooks\Http\Controllers\WebhooksController as Controller;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function __construct()
    {
        if (config('paystackwebhooks.quickcred_secret') || config('paystackwebhooks.quickfund_secret')) {
            $this->middleware(PayStackVerifyWebhookSignature::class);
        }
    }

    public function handleChargeSuccess($payload)
    {
        //handle for virtual account payment.
        if ($payload['data']['channel'] == 'dedicated_nuban'){
            HandleDVACreditWebhook::dispatch($payload);
            return response()->json(['success' => true]);
        }

        //check if temp transaction exists
        $tempTransaction = GatewayTempTransaction::whereReference($payload['data']['reference'])->first();

        //$transaction = GatewayTransaction::whereReference($payload['data']['reference'])->whereType(TransactionEnum::TYPE['account_charge'])->first();

        if ( ! $tempTransaction ) return;

        HandleWebhookJob::dispatch('success_event', $payload, $tempTransaction);

        return response()->json(['success' => true]);
    }

       public function handleChargeFailed($payload)
    {
        //check if temp transaction exists
        $tempTransaction = GatewayTempTransaction::whereReference($payload['data']['reference'])->first();

        //$transaction = GatewayTransaction::whereReference($payload['data']['reference'])->whereType(TransactionEnum::TYPE['account_charge'])->first();

        if ( ! $tempTransaction ) return;

        HandleWebhookJob::dispatch('failed_event', $payload, $tempTransaction);

        return response()->json(['success' => true]);
    }

    public function handleDirectDebitAuthorizationCreated($payload)
    {
        Log::info('Direct Debit Authorization Created', $payload);

        //check if temp transaction exists
        $tempTransaction = GatewayTempTransaction::whereReference($payload['data']['reference'])->first();

        if ( ! $tempTransaction ) return;


        return response()->json(['success' => true]);
    }

    public function handleDirectDebitAuthorizationActive($payload)
    {
        //check if temp transaction exists
        $tempTransaction = GatewayTempTransaction::whereReference($payload['data']['reference'])->first();

        if ( ! $tempTransaction ) return;

        HandleWebhookJob::dispatchAfterResponse('success_event', $payload, $tempTransaction);

        return response()->json(['success' => true]);
    }

    public function handleTransferFailed($payload)
    {
        //check if temp transaction exists
        $tempTransaction = GatewayTempTransaction::whereReference($payload['data']['reference'])->first();

        if ( ! $tempTransaction ) return;

        HandleWebhookJob::dispatch('failed_event', $payload, $tempTransaction);

        return response()->json(['success' => true]);
    }

    public function handleTransferSuccess($payload)
    {
        //check if temp transaction exists
        $tempTransaction = GatewayTempTransaction::whereReference($payload['data']['reference'])->first();

        if ( ! $tempTransaction ) return;

        HandleWebhookJob::dispatch('success_event', $payload, $tempTransaction);

        return response()->json(['success' => true]);
    }

    public function handleTransferReversed($payload)
    {
        //check if temp transaction exists
        $tempTransaction = GatewayTempTransaction::whereReference($payload['data']['reference'])->first();

        if ( ! $tempTransaction ) return;

        $event = TransactionEnum::TYPE['transfer'].'.reversed';

        HandleWebhookJob::dispatch(
            'failed_event',
            $payload, $tempTransaction,
            $event,
            TransactionEnum::STATUS['reversed'],
        );

        return response()->json(['success' => true]);
    }

    public function handleDedicatedAccountAssignFailed($payload)
    {
        $customer = Customer::whereEmail($payload['data']['customer']['email']);

        if ( ! $customer ) return;

        HandleWebhookJob::dispatch(
            'virtual_account_creation_failed',
            $payload, $customer
        );

        return response()->json(['success' => true]);
    }

    public function handleDedicatedAccountAssignSuccess($payload)
    {
        $customer = Customer::whereEmail($payload['data']['customer']['email']);

        if ( ! $customer ) return;

        HandleWebhookJob::dispatch(
            'virtual_account_creation_success',
            $payload, $customer
        );

        return response()->json(['success' => true]);
    }

    public function handleCustomerIdentificationFailed($payload)
    {
        $customer = Customer::whereEmail($payload['data']['email']);

        if ( ! $customer ) return;

        HandleWebhookJob::dispatch(
            'customer_identity_failed',
            $payload, $customer
        );

        return response()->json(['success' => true]);
    }
}
