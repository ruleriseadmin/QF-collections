<?php

namespace App\Actions\DirectDebit;

use App\Service\PayStack\PayStackDirectDebitService;
use Exception;
use App\Models\Gateway\AccessGateway;
use Illuminate\Support\Facades\Log;

class VerifyDirectDebitAction
{
    public function execute(AccessGateway $accessGateway, string $reference)
    {
        try{
            $verify = PayStackDirectDebitService::verifyAuthCode($reference);
            return $verify;
        }catch(Exception $ex){
            Log::error('Error @ VerifyDirectDebitAction: ' . $ex->getMessage());
            return null;
        }
    }
}
