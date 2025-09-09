<?php

namespace App\Actions\Transfer;

use App\Exceptions\ApiException;
use Exception;
use App\Enums\TransactionEnum;
use Illuminate\Support\Facades\Log;
use App\Models\Gateway\AccessGateway;
use App\Service\PayStack\PayStackTransferService;

class InitiateTransferViaRecipientCodeAction
{
    public function execute(AccessGateway $accessGateway, array $input)
    {
        try{
            $initiateTransfer = (object) PayStackTransferService::initiateTransfer(
                amountInKobo: $input['amountInKobo'],
                recipientCode: $input['recipientCode'],
                description: $input['description'] ?? 'Transfer',
                reference: $input['reference'] ?? null,
            );

            $transaction = $accessGateway->tempTransactions()->create([
                'type' => $initiateTransfer->isOtpEnabled ? TransactionEnum::TYPE['initiate_transfer'] : TransactionEnum::TYPE['transfer'],
                'amount' => $input['amountInKobo'],
                'provider' => TransactionEnum::PROVIDERS['paystack'],
                'status' => TransactionEnum::STATUS['pending'],
                'meta' => $initiateTransfer,
                'reference' => $initiateTransfer->reference,
            ]);

            return $transaction;
        }catch(Exception $ex){
            Log::error('Error @ InitiateTransferAction: ' . $ex->getMessage());
            throw new ApiException($ex->getMessage()); //;Exception('Error @ InitiateTransferAction: ' . $ex->getMessage());
        }
    }
}
