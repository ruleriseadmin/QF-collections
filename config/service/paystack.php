<?php

return [
    'base_url' => env('PAYSTACK_BASE_URL', 'https://api.paystack.co'),
    'secret_key' => env('PAYSTACK_SECRET_KEY'),
    
    'quickcred' => [
        'base_url' => env('PAYSTACK_QUICKCRED_BASE_URL', 'https://api.paystack.co'),
        'secret_key' => env('PAYSTACK_QUICKCRED_SECRET_KEY'),
    ],
    'quickfund' => [
        'base_url' => env('PAYSTACK_QUICKFUND_BASE_URL', 'https://api.paystack.co'),
        'secret_key' => env('PAYSTACK_QUICKFUND_SECRET_KEY'),
    ],
];
