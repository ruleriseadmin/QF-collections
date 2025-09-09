<?php

namespace App\Service\PayStack;

class PayStackDVAService extends PaystackService
{
    //api reference https://paystack.com/docs/payments/dedicated-virtual-accounts/#single-step-account-assignment
    public static function createVirtualAccount(array $payload)
    {
        return self::request('post', 'dedicated_account/assign', $payload);
    }
}
