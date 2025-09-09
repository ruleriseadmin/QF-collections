<?php

namespace App\Http\Controllers;

use App\Actions\DirectDebit\GetRevokedMandatesAction;
use App\Supports\ApiReturnResponse;
use App\Actions\DirectDebit\VerifyDirectDebitAction;
use App\Actions\DirectDebit\InitiateDirectDebitAction;
use App\Http\Requests\DirectDebit\InitiateDirectDebitRequest;

class DirectDebitController extends BaseController
{
    public function initiate(InitiateDirectDebitRequest $request)
    {
        $directDebit = (new InitiateDirectDebitAction)
            ->execute($this->accessGateway, $request->validated());

        return $directDebit ? ApiReturnResponse::success($directDebit) : ApiReturnResponse::failed();
    }

    public function verifyDirectDebit(string $reference)
    {
        $getStatus = (new VerifyDirectDebitAction)->execute($this->accessGateway, $reference);

        return $getStatus ? ApiReturnResponse::success($getStatus) : ApiReturnResponse::failed();
    }

    public function getRevokedMandates()
    {
        $revokedMandates = (new GetRevokedMandatesAction)->execute($this->accessGateway);

        return $revokedMandates ? ApiReturnResponse::success($revokedMandates) : ApiReturnResponse::failed();
    }
}
