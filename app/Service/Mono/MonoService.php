<?php

namespace App\Service\Mono;

use App\Supports\HelperSupport;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MonoService
{
    protected static function request(string $method, string $url, array $payload = [])
    {
        try{
            $response = Http::withHeaders([
                'mono-sec-key' => config('service.mono.secret_key'),
            ])
                ->baseUrl(config('service.mono.base_url'))
                ->$method($url, $payload);
        }catch(Exception $ex){
            Log::error('Error at MonoService: '.$ex->getMessage());
            throw new Exception('Error requesting MonoService Api');
        }

        return $response->json();
    }

    //api reference https://docs.mono.co/api/customer/create-a-customer
    public static function createCustomer(array $payload)
    {
        $request = self::request('post', 'customers', HelperSupport::camel_to_snake($payload));

        if ( $request['status'] != 'successful' ){
            return [
                'success' => false,
                'message' => $request['message'],
                'existingId' => $request['data']['existing_customer']['id'] ?? null,
            ];
        }

        return [
            'success' => true,
            'request' => $request['data'],
            'message' => $request['message'],
        ];
    }

    //api reference https://docs.mono.co/api/direct-debit/mandate/initiate-mandate-authorisation
    public static function initiateGSM(array $payload)
    {
        $request = self::request('post', 'payments/initiate', [
            'reference' => $payload['reference'],
            'type' => 'recurring-debit',
            'amount' => $payload['amount'],
            'description' => $payload['description'] ?? 'GSI',
            'mandate_type' => 'gsm',
            'method' => 'mandate',
            'debit_type' => 'variable',//$payload['debitType'],
            'redirect_url' => $payload['callbackUrl'],
            'customer' => [
                'id' => $payload['customerId'],
            ],
            'start_date' => $payload['startDate'],
            'end_date' => $payload['endDate'],
        ]);

        if ( $request['status'] != 'successful' ){
            return [
                'success' => false,
                'message' => $request['message'],
            ];
        }

        $data = (object) $request['data'];

        return [
            'success' => true,
            'request' => $request['data'],
            'message' => $request['message'],
            'url' => $data->mono_url,
        ];
    }
}
