<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Supports\ApiReturnResponse;
use App\Http\Controllers\BaseController;
use App\Actions\CardTokenization\ChargeCardAction;
use App\Actions\PaymentLink\GeneratePaymentLinkAction;
use App\Http\Requests\ChargeAccountRequest;
use App\Http\Requests\CardTokenization\InitiateCardTokenizationRequest;

class CardTokenizationsController extends BaseController
{
    public function initiateCardTokenization(InitiateCardTokenizationRequest $request): JsonResponse
    {
        $input = $request->input();

        $input['amountInKobo'] = 20000;

        $paymentLink = (new GeneratePaymentLinkAction)->execute($this->accessGateway,$input, true);

        return $paymentLink ? ApiReturnResponse::success($paymentLink) : ApiReturnResponse::failed();
    }

    public function chargeCard(ChargeAccountRequest $request)
    {
        $chargeCard = (new ChargeCardAction)->execute($this->accessGateway, $request->validated());

        return $chargeCard ? ApiReturnResponse::success() : ApiReturnResponse::failed();
    }
}
