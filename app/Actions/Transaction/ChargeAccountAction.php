<?php

namespace App\Actions\Transaction;

use Exception;
use App\Traits\CustomerTrait;
use App\Enums\TransactionEnum;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\Log;
use App\Models\Gateway\AccessGateway;
use App\Service\PayStack\PaystackService;

class ChargeAccountAction
{
    use CustomerTrait;

    public function execute(AccessGateway $accessGateway, array $input)
    {
        $customer = $this->retrieveCustomer($accessGateway, $input['email']);

        try{
            $chargeAccount = (object) PaystackService::chargeAuthorization(
                email: $input['email'],
                authorizationCode: $input['authorizationCode'],
                amountInKobo: $input['amountInKobo'],
                reference: $input['reference'] ?? null,
            );

            $transaction = $accessGateway->tempTransactions()->create([
                'type' => TransactionEnum::TYPE['account_charge'],
                'customer_id' => $customer->id,
                'reference' => $chargeAccount->request['reference'],
                'provider' => TransactionEnum::PROVIDERS['paystack'],
                'meta' => $chargeAccount,
                'status' => $chargeAccount->success ? TransactionEnum::STATUS['success'] : TransactionEnum::STATUS['failed'],
                'amount' => $input['amountInKobo'],
            ]);

            return $transaction;
        }catch(Exception $ex){
            Log::error('Error @ ChargeAccountAction: ' . $ex->getMessage());
            throw new ApiException('Error @ ChargeAccountAction: ' . $ex->getMessage());
        }
    }
}
