<?php

namespace App\Http\Requests\GSI;

use App\Http\Requests\BaseRequest;

class MonoProfileCustomerRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'identity.type' => ['required', 'in:nin,bvn'],
            'identity' => ['required', 'array'],
            'identity.number' => ['required'],
            //'type' => ['required'],
            'lastName' => ['required'],
            'firstName' => ['required'],
            'address' => ['required'],
            //'phoneNumber' => ['required'],
            'email' => ['required', 'email'],
        ];
    }
}
