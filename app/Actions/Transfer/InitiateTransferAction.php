<?php

namespace App\Actions\Transfer;

use Exception;
use App\Enums\TransactionEnum;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\Log;
use App\Models\Recipients\Recipient;
use App\Models\Gateway\AccessGateway;
use App\Service\PayStack\PayStackTransferService;

class InitiateTransferAction
{
    private array $input;

    public function execute(AccessGateway $accessGateway, array $input)
    {
        $this->input = $input;

        try{
            $recipientCode = $this->retrieveRecipient($accessGateway, $input['accountNumber']);

            $initiateTransfer = (object) PayStackTransferService::initiateTransfer(
                amountInKobo: $input['amountInKobo'],
                recipientCode: $recipientCode,
                description: $input['description'] ?? 'Transfer',
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
            throw new ApiException('Error @ InitiateTransferAction: ' . $ex->getMessage());
        }
    }

    private function retrieveRecipient(AccessGateway $accessGateway , string $accountNumber): mixed
    {
        $recipient = $accessGateway->recepients()->whereAccountNumber($accountNumber)->first();

        if ( $recipient ) return $recipient->identifier;

        $recipient = (new CreateTransferRecipientAction)->execute($accessGateway, $this->input);

        return $recipient->identifier;
    }
}
