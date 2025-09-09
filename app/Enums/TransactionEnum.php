<?php

namespace App\Enums;

enum TransactionEnum
{
    const STATUS = [
        'pending' => 'pending',
        'success' => 'success',
        'failed' => 'failed',
        'reversed' => 'reversed',
    ];


    const PROVIDERS = [
        'paystack' => 'paystack',
    ];

    const TYPE = [
        'payment_link' => 'payment_link',
        'card_tokenization' => 'card_tokenization',
        'partial_payment' => 'partial_payment',
        'account_charge' => 'account_charge',
        'initiate_transfer' => 'initiate_transfer',
        'transfer' => 'transfer',
        'direct_debit' => 'direct_debit',
        'virtual_account_fund' => 'virtual_account_fund',
    ];
}