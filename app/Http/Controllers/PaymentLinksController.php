<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Supports\ApiReturnResponse;
use App\Actions\PaymentLink\GeneratePaymentLinkAction;
use App\Http\Requests\PaymentLink\GeneratePaymentLinkRequest;
use App\Http\Requests\CardTokenization\InitiateCardTokenizationRequest;

class PaymentLinksController extends BaseController
{
    public function generatePaymentLink(GeneratePaymentLinkRequest $request): JsonResponse
    {
        $paymentLink = (new GeneratePaymentLinkAction)->execute($this->accessGateway,$request->input());

        return $paymentLink ? ApiReturnResponse::success($paymentLink['meta']) : ApiReturnResponse::failed();
    }
}
