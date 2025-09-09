<?php

namespace App\Actions\PaymentLink;

use App\Enums\TransactionEnum;
use App\Exceptions\ApiException;
use App\Models\Gateway\AccessGateway;
use App\Service\PayStack\PayStackPaymentLink;
use App\Traits\CustomerTrait;
use Exception;

class GeneratePaymentLinkAction
{
    use CustomerTrait;

    public function execute(AccessGateway $accessGateway, array $input, bool $cardToken = false)
    {
        try{
            $customer = $this->retrieveCustomer($accessGateway, $input['email']);

            $paystackMethod = $cardToken ? 'createPaymentLinkForCard' : 'createPaymentLink';

            $request = PayStackPaymentLink::$paystackMethod(
                amount: $input['amountInKobo'],
                customerEmail: $input['email'],
                callbackUrl: $input['callbackUrl'] ?? '',
            );

            $transaction = $accessGateway->tempTransactions()->create([
                'customer_id' => $customer->id,
                'type' => $cardToken ? TransactionEnum::TYPE['card_tokenization'] : TransactionEnum::TYPE['payment_link'],
                'provider' => TransactionEnum::PROVIDERS['paystack'],
                'status' => TransactionEnum::STATUS['pending'],
                'reference' => $request['reference'],
                'meta' => $request,
                'amount' => $input['amountInKobo'],
            ]);

            $transaction['amount'] = $input['amountInKobo'];

            return $transaction;
        }catch(Exception $ex){
            throw new ApiException('Error @ GeneratePaymentLinkAction: ' . $ex->getMessage());
        }
    }
}
