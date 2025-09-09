<?php

namespace App\Actions\DirectDebit;

use Exception;
use App\Traits\CustomerTrait;
use App\Enums\TransactionEnum;
use App\Exceptions\ApiException;
use App\Models\DirectDebit\RevokedMandate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Gateway\AccessGateway;
use App\Traits\TransactionResourceTrait;
use App\Service\PayStack\PayStackDirectDebitService;

class InitiateDirectDebitAction
{
    use CustomerTrait, TransactionResourceTrait;

    public function execute(AccessGateway $accessGateway, array $input)
    {
        $customer = $this->retrieveCustomer($accessGateway, $input['email']);

        if ( collect($input)->has('account') && collect($customer
                ->directDebitTransactions)->isNotEmpty() ){
            //check if customer has direct debit account
            $lastFour = substr($input['account']['number'], -4);

            $directDebitTransaction = $customer
                ->transactions()->where('type', 'direct_debit')
                ->whereJsonContains('meta->data->last4', $lastFour)
                ->orWhereJsonContains('meta->last4', $lastFour)
                ->first();

           
           

            if ($directDebitTransaction) {
                $authorizationCode = $directDebitTransaction->meta['authorization_code'] ?? null;

                if ($authorizationCode && $this->isRevoked($authorizationCode)) {
                } else {
                  return $this->directDebitResponse($directDebitTransaction->meta);
                }
            }
        }


        DB::beginTransaction();
        try{
            $directDebit = PayStackDirectDebitService::initialize(
                email: $input['email'],
                callbackUrl: $input['callbackUrl'] ?? '',
                account: $input['account'] ?? null,
                address: $input['address'] ?? null,
            );

            $accessGateway->tempTransactions()->create([
                'customer_id' => $customer->id,
                'type' => TransactionEnum::TYPE['direct_debit'],
                'provider' => TransactionEnum::PROVIDERS['paystack'],
                'status' => TransactionEnum::STATUS['pending'],
                'meta' => $directDebit,
                'reference' => $directDebit['reference'],
            ]);

            // TempDirectDebit::create([
            //     'customer_id' => $customer->id,
            //     'access_gateway_id' => $accessGateway->id,
            //     'provider' => TempDirectDebit::PROVIDERS['paystack'],
            //     'reference' => $directDebit['reference'],
            //     'meta' => $directDebit,
            // ]);

            DB::commit();
            return $directDebit;
        }catch(Exception $ex){
            DB::rollBack();
            Log::error('Error at InitiateDirectDebitAction: '.$ex->getMessage());
            throw new ApiException($ex->getMessage());
        }
    }

    public function isRevoked(string $authorizationCode): bool
    {
        return RevokedMandate::where('mandate_code', $authorizationCode)->exists();
    }

}
