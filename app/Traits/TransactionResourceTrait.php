<?php

namespace App\Traits;

use Illuminate\Support\Arr;

trait TransactionResourceTrait
{
    private function directDebitResponse($meta): array
    {
        $data = $meta['data'] ?? $meta;

        return [
            'authorizationCode' => $data['authorization_code'],
            'reusable' => $data['reusable'],
            'expYear' => $data['exp_year'],
            'expMonth' => $data['exp_month'],
            'last4' => $data['last4'],
            'active' => $data['active'],
        ];
    }

    private function cardTokenizationResponse($meta)
    {
        $data = $meta['data'];

        $authorization = $data['authorization'];

        return [
            'authorizationCode' => $authorization['authorization_code'],
            'reusable' => $authorization['reusable'],
            'expYear' => $authorization['exp_year'],
            'expMonth' => $authorization['exp_month'],
            'last4' => $authorization['last4'],
            'cardType' => $authorization['card_type'],
            'bank' => $authorization['bank'],
            'brand' => $authorization['brand'],
        ];
    }

    private function accountCharge($meta)
    {
        $amount = match (true) {
            Arr::has($meta, 'data.amount') => $meta['data']['amount'],
            Arr::has($meta, 'request.amount') => $meta['request']['amount'],
            default => null,
        };

        $gateway_response = match (true) {
            Arr::has($meta, 'data.gateway_response') => $meta['data']['gateway_response'],
            Arr::has($meta, 'request.gateway_response') => $meta['request']['gateway_response'],
            default => null,
        };

        $message = match (true) {
            Arr::has($meta, 'data.message') => $meta['data']['message'],
            Arr::has($meta, 'request.message') => $meta['request']['message'],
            default => null,
        };

        return [
            'amountChargedInKobo' => $amount,
            'gateway_response' => $gateway_response,
            'message' => $message
        ];
    }
}
