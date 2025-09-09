<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
use App\Supports\ApiReturnResponse;
use App\Actions\Transaction\ChargeAccountAction;
use App\Http\Requests\ChargeAccountRequest;

class ChargeAccountController extends BaseController
{
    public function chargeAccount(ChargeAccountRequest $request)
    {
        $chargeAccount = (new ChargeAccountAction)->execute($this->accessGateway, $request->validated());

        return $chargeAccount ? ApiReturnResponse::success(new TransactionResource($chargeAccount)) : ApiReturnResponse::failed();
    }
}
