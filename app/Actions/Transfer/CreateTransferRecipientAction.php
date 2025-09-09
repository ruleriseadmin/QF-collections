<?php

namespace App\Actions\Transfer;

use App\Exceptions\ApiException;
use App\Models\Gateway\AccessGateway;
use App\Models\Recipients\Recipient;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Service\PayStack\PayStackTransferService;

class CreateTransferRecipientAction
{
    public function execute(AccessGateway $accessGateway, array $input)
    {
        $recipient = $accessGateway->recepients()->whereAccountNumber($input['accountNumber'])->first();

        if ( $recipient ) return $recipient;

        try{
            $createRecipient = (object) PayStackTransferService::createRecipient(
                name: $input['name'],
                accountNumber: $input['accountNumber'],
                bankCode: $input['bankCode'],
            );

            $recipient = $accessGateway->recepients()->create([
                'identifier' => $createRecipient->identifier ?? null,
                'name' => $input['name'],
                'account_number' => $input['accountNumber'],
                'bank_code' => $input['bankCode'],
                'meta' => $createRecipient->request,
            ]);
            return $recipient;
        }catch(Exception $ex){
            Log::error('Error @ CreateTransferRecipientAction: ' . $ex->getMessage());
            throw new ApiException('Error @ CreateTransferRecipientAction: ' . $ex->getMessage());
        }
    }
}
