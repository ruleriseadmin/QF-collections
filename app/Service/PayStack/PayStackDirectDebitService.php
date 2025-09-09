<?php

namespace App\Service\PayStack;

use Exception;
use Illuminate\Support\Facades\Log;

class PayStackDirectDebitService extends PaystackService
{
    //api reference https://pilot-direct-debit.d111ulzzlm5kvf.amplifyapp.com/docs/payments/direct-debit/#initiate-an-authorization-request
    public static function initialize(string $email, ?string $callbackUrl = null, ?array $account = null, ?array $address = null)
    {
        $payload = [
            'email' => $email,
            'channel' => 'direct_debit',
        ];
        $callbackUrl && $payload['callback_url'] = $callbackUrl;

        $account && $payload['account'] = $account;

        $address && $payload['address'] = $address;

        $request = self::request('post', 'customer/authorization/initialize', $payload);

        if ( ! $request['status'] ) {
            Log::error($request);
            throw new Exception($request['message'] ?? 'Error from PayStack on direct debit initialize.');
        }

        return [
            'reference' => $request['data']['reference'],
            'url' => $request['data']['redirect_url'],
            'accessCode' => $request['data']['access_code'],
        ];
    }

    // api reference https://paystack.com/docs/api/directdebit/#mandate-authorizations
    public static function getRevokedMandates()
    {
        $request = self::request('get', 'directdebit/mandate-authorizations', ['status' => 'revoked', 'per_page' => 200]);

        if ( ! $request['status'] ) {
            Log::error($request);
            throw new Exception($request['message'] ?? 'Error from PayStack on get revoked mandates.');
        }

        return $request['data'];
    }
}
