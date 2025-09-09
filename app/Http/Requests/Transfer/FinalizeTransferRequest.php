<?php

namespace App\Http\Requests\Transfer;

use App\Http\Requests\BaseRequest;

class FinalizeTransferRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'reference' => ['required', 'exists:gateway_temp_transactions,reference'],
            'otp' => ['required'],
        ];
    }
}
