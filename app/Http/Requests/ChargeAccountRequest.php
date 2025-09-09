<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class ChargeAccountRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required'],
            'amountInKobo' => ['required'],
            'authorizationCode' => ['required'],
            'reference' => ['nullable', 'string']
        ];
    }
}
