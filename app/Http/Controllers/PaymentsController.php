<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartialPaymentRequest;
use App\Actions\Payment\ProcessPartialPaymentAction;
use App\Http\Resources\TransactionResource;
use App\Supports\ApiReturnResponse;

class PaymentsController extends BaseController
{
    public function partialPayment(PartialPaymentRequest $request)
    {
        $transaction = (new ProcessPartialPaymentAction)->execute($this->accessGateway, $request->validated());

        return $transaction ? ApiReturnResponse::success(new  TransactionResource($transaction)) : ApiReturnResponse::failed();
    }
}
