<?php

namespace App\Service\PayStack;

use App\Actions\AccessGateway\GeneratePaystackCredentialsAction;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackService
{

    protected static function request(string $method, string $url, array $payload = [])
    {
        try {
            $credentials  = (new GeneratePaystackCredentialsAction)->execute();
            $secretKey = $credentials['paystack_secret_key'];

            $response = Http::withToken($secretKey)
                ->baseUrl(config('service.paystack.base_url'))
                ->$method($url, $payload);
        } catch (Exception $ex) {
            Log::error('Error at PaystackService: ' . $ex->getMessage());
            throw new Exception('Error requesting Paystack Api');
        }

        return $response->json();
    }

    //api reference https://paystack.com/docs/api/transaction/
    protected static function generatePaymentToken($payload)
    {
        $request = self::request('post', 'transaction/initialize', $payload);

        if (! $request['status']) {
            Log::error(collect($request));
            throw new Exception($request['message'] ?? 'Error from PayStack on transaction initialize.');
        }

        return [
            'reference' => $request['data']['reference'],
            'url' => $request['data']['authorization_url'],
            'accessCode' => $request['data']['access_code'],
        ];
    }

    //api reference https://paystack.com/docs/api/transaction/#charge-authorization
    public static function chargeAuthorization(string $email, string $authorizationCode, string $amountInKobo, ?string $reference = null)
    {

       
        $data = [
            'email' => $email,
            'amount' => $amountInKobo,
            'authorization_code' => $authorizationCode,
        ];

        if ($reference) {
            $data['reference'] = $reference;
        }


        $request = self::request('post', 'transaction/charge_authorization', $data);


        if (! $request['status']) {
            Log::error(collect($request));
            throw new Exception($request['message'] ?? 'Error from PayStack on charge authorization.');
        }

        return [
            'success' => strtolower($request['data']['status']) == 'success',
            'request' => $request['data'],
        ];
    }

    //api reference https://paystack.com/docs/api/transaction/#partial-debit
    public static function partialDebit(string $email, string $authorizationCode, string $amountInKobo, string $atLeast, ?string $reference = null)
    {
        $data = [
            'email' => $email,
            'amount' => $amountInKobo,
            'authorization_code' => $authorizationCode,
            'currency' => 'NGN',
            'at_least' => $atLeast,
        ];

         if ($reference) {
            $data['reference'] = $reference;
        }

        $request = self::request('post', 'transaction/partial_debit', $data);

        if (! $request['status'] ||  $request['data']['status'] !== 'success') {
            Log::error(collect($request));
            throw new Exception($request['data']['gateway_response'] ?? 'Error from PayStack on partial debit.');
        }

        return [
            'success' => strtolower($request['data']['status']) == 'success',
            'request' => $request['data'],
        ];
    }

    public static function verifyAuthCode(string $reference)
    {
        $request = self::request('get', "customer/authorization/verify/$reference");

        if (! $request['status']) {
            Log::error(collect($request));
            return [
                'active' => false,
                'message' => $request['message'],
                'meta' => null,
            ];
        }

        return [
            'active' => true,
            'message' => $request['message'],
            'meta' => $request['data'],
        ];
    }

    public static function verifyTransaction(string $reference)
    {
        $request = self::request('get', "transaction/verify/$reference");

        if (! $request['status']) {
            Log::error(collect($request));
            return [
                'active' => false,
                'message' => $request['message'],
                'meta' => null,
            ];
        }

        return [
            'active' => strtolower($request['data']['status']) == 'success',
            'message' => $request['data']['gateway_response'] ?? $request['message'],
            'meta' => $request['data'],
        ];
    }
}
