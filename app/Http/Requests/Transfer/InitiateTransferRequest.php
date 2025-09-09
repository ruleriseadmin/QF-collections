<?php

namespace App\Http\Requests\Transfer;

use App\Http\Requests\BaseRequest;

class InitiateTransferRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'accountNumber' => ['required', 'digits:10'],
            'bankCode' => ['required'],
            'amountInKobo' => ['required'],
            'description' => ['nullable', 'string'],
        ];
    }
}
