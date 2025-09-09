<?php

namespace App\Http\Requests\CardTokenization;

use App\Http\Requests\BaseRequest;

class InitiateCardTokenizationRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required'],
            'callbackUrl' => ['nullable'],
        ];
    }
}
