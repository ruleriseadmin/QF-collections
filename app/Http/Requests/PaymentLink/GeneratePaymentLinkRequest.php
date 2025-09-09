<?php

namespace App\Http\Requests\PaymentLink;

use App\Http\Requests\BaseRequest;

class GeneratePaymentLinkRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required'],
            'amountInKobo' => ['required'],
            'callbackUrl' => ['nullable'],
        ];
    }
}
