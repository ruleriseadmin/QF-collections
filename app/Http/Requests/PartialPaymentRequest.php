<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class PartialPaymentRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required'],
            'amountInKobo' => ['required'],
            'authorizationCode' => ['required'],
            'atLeast' => ['nullable'],
            'reference' => ['nullable', 'string']
        ];
    }
}
