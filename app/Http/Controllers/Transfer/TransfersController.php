<?php

namespace App\Http\Controllers\Transfer;

use App\Supports\ApiReturnResponse;
use App\Http\Controllers\BaseController;
use App\Http\Resources\TransactionResource;
use App\Actions\Transfer\FinalizeTransferAction;
use App\Actions\Transfer\InitiateTransferAction;
use App\Actions\Transfer\CreateTransferRecipientAction;
use App\Http\Requests\Transfer\FinalizeTransferRequest;
use App\Http\Requests\Transfer\InitiateTransferRequest;
use App\Http\Requests\Transfer\CreateTransferRecipientRequest;
use App\Actions\Transfer\InitiateTransferViaRecipientCodeAction;
use App\Http\Requests\Transfer\InitiateTransferViaRecipientCodeRequest;

class TransfersController extends BaseController
{
    public function initiateTransfer(InitiateTransferRequest $request)
    {
        $transaction = (new InitiateTransferAction)->execute($this->accessGateway, $request->validated());

        return $transaction
            ? ApiReturnResponse::success(new TransactionResource($transaction))
            : ApiReturnResponse::failed();
    }

    public function finalizeTransfer(FinalizeTransferRequest $request)
    {
        $transaction = (new FinalizeTransferAction)->execute($this->accessGateway, $request->validated());

        return $transaction
            ? ApiReturnResponse::success(new TransactionResource($transaction))
            : ApiReturnResponse::failed();
    }

    public function createTransferRecipient(CreateTransferRecipientRequest $request)
    {
        $recipient = (new CreateTransferRecipientAction)->execute($this->accessGateway, $request->validated());

        $response = $request->validated();

        $recipient && $response['identifier'] = $recipient->identifier;

        return $recipient
            ? ApiReturnResponse::success($response)
            : ApiReturnResponse::failed();
    }

    public function initiateTransferViaRecipientCode(InitiateTransferViaRecipientCodeRequest $request)
    {
        $transaction = (new InitiateTransferViaRecipientCodeAction)->execute($this->accessGateway, $request->validated());

        return $transaction
            ? ApiReturnResponse::success(new TransactionResource($transaction))
            : ApiReturnResponse::failed();
    }
}
