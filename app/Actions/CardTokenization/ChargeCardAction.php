<?php

namespace App\Actions\CardTokenization;

use App\Exceptions\ApiException;
use App\Models\Gateway\AccessGateway;
use App\Service\PayStack\PaystackService;
use Exception;
use Illuminate\Support\Facades\Log;

class ChargeCardAction
{
    public function execute(AccessGateway $accessGateway, array $input)
    {
        try{
            $chargeCard = PaystackService::chargeAuthorization(
                email: $input['email'],
                authorizationCode: $input['authorizationCode'],
                amountInKobo: $input['amount'],
                reference: $input['reference'] ?? null,
            );
            return $chargeCard;
        }catch(Exception $ex){
            Log::error('Error @ ChargeCardAction: ' . $ex->getMessage());
            throw new ApiException('Error @ ChargeCardAction: ' . $ex->getMessage());
        }
    }
}
