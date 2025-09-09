<?php

namespace App\Http\Requests\DirectDebit;

use App\Http\Requests\BaseRequest;
use Illuminate\Contracts\Validation\Validator;

class InitiateDirectDebitRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'account' => ['nullable'],
            'address' => ['nullable'],
            'callbackUrl' => ['nullable'],
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator){
            if ( $this->filled('account') ){
                $this->filled('account.number') ? : $validator->errors()->add('account.number', 'account number is required');
                $this->input('account.bank_code') ? : $validator->errors()->add('account.bank_code', 'bank code is required');
            }

            if ( $this->filled('address') ){
                $this->input('address.state') ? : $validator->errors()->add('account.state', 'State is required');
                $this->input('address.city') ? : $validator->errors()->add('account.city', 'City is required');
                $this->input('address.street') ? : $validator->errors()->add('account.street', 'Street is required');
            }
        });
    }
}
