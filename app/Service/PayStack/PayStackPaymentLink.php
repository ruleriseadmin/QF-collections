<?php

namespace App\Service\PayStack;

use Exception;

class PayStackPaymentLink extends PaystackService
{
    public static function createPaymentLink($amount, $customerEmail, ?string $callbackUrl = null)
    {
        return self::generatePaymentToken([
            'amount' => $amount,
            'email' => $customerEmail,
            'callback_url' => $callbackUrl ?? '',
        ]);
    }

    public static function createPaymentLinkForCard($amount, $customerEmail, ?string $callbackUrl = null)
    {
        return self::generatePaymentToken([
            'amount' => $amount,
            'email' => $customerEmail,
            'callback_url' => $callbackUrl ?? '',
            'channels' => ['card'],
        ]);
    }
}
