<?php

namespace App\Http\Requests\Transfer;

use App\Http\Requests\BaseRequest;

class InitiateTransferViaRecipientCodeRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'amountInKobo' => ['required'],
            'description' => ['nullable', 'string'],
            'recipientCode' => ['required', 'exists:recipients,identifier'],
            'reference' => ['nullable', 'string']
        ];
    }
}
