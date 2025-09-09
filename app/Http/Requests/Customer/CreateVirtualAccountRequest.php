<?php

namespace App\Http\Requests\Customer;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class CreateVirtualAccountRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required'],
            'preferred_bank' => ['required'],
            'first_name' => ['required'],
            //'middle_name' => ['required'],
            'last_name' => ['required'],
            'phone' => ['required', 'digits:11'],
            'account_number' => ['required', 'digits:10'],
            'bvn' => ['required', 'digits:11'],
            'bank_code' => ['required'],
        ];
    }
}
