<?php

namespace App\Actions\Payment;

use App\Enums\TransactionEnum;
use App\Exceptions\ApiException;
use App\Models\Gateway\AccessGateway;
use App\Service\PayStack\PaystackService;
use App\Traits\CustomerTrait;
use Exception;
use Illuminate\Support\Facades\Log;

class ProcessPartialPaymentAction
{
    use CustomerTrait;

    public function execute(AccessGateway $accessGateway, array $input)
    {
        $customer = $this->retrieveCustomer($accessGateway, $input['email']);

        try{
            $requestPartialPayment = (object) PaystackService::partialDebit(
                email: $input['email'],
                authorizationCode: $input['authorizationCode'],
                amountInKobo: $input['amountInKobo'],
                atLeast: $input['atLeast'],
                reference: $input['reference'] ?? null,
            );

            $transaction = $accessGateway->transactions()->create([
                'type' => TransactionEnum::TYPE['partial_payment'],
                'customer_id' => $customer->id,
                'reference' => $requestPartialPayment->request['reference'],
                'provider' => TransactionEnum::PROVIDERS['paystack'],
                'meta' => $requestPartialPayment,
                'status' => $requestPartialPayment->success ? TransactionEnum::STATUS['success'] : TransactionEnum::STATUS['failed'],
            ]);

            return $transaction;
        }catch(Exception $ex){
            Log::error('Error @ ProcessPartialPaymentAction: ' . $ex->getMessage());
            throw new ApiException('Error @ ProcessPartialPaymentAction: ' . $ex->getMessage());
        }
    }
}
