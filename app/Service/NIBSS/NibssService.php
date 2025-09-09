<?php

namespace App\Service\NIBSS;

use App\Models\ExternalServiceAuthToken;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NibssService
{
    protected static function request(string $method, string $url, array $payload = [])
    {
        $authToken = ExternalServiceAuthToken::getAuthToken(ExternalServiceAuthToken::NIBSS);

        if ( ! $authToken ){
            try{
                $response = Http::baseUrl(config('service.nibss.base_url'))->withHeaders([
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'apikey' => config('service.nibss.api_key'),
                ])->asForm()->post('/v2/reset', [
                        'grant_type' => config('service.nibss.grant_type'),
                        'client_secret' => config('service.nibss.client_secret'),
                        'client_id' => config('service.nibss.client_id'),
                        'scope' => config('service.nibss.scope'),
                    ])->json();

                $authToken = $response;

                if ( ! array_key_exists('access_token', $authToken) ){
                    Log::error('Error at NibssService - Could not get access token: '. $authToken);
                    throw new Exception('Error requesting NibssService Api');
                }

                ExternalServiceAuthToken::setAuthToken(ExternalServiceAuthToken::NIBSS, $authToken['access_token'], $authToken['expires_in']);

                $authToken = $authToken['access_token'];
            }catch(Exception $ex){
                Log::error('Error at NibssService - getAccessToken '.$ex->getMessage());
                throw new Exception('Error requesting NibssService Api');
            }
        }

        try{
            $response = Http::withToken($authToken)
                ->baseUrl(config('service.nibss.base_url'))
                ->$method($url, $payload);
        }catch(Exception $ex){
            Log::error("Error at NibssService($url): {$ex->getMessage()}");
            throw new Exception('Error requesting NibssService Api');
        }

        return $response->json();
    }

    public static function createEMandate(array $input)
    {
         dd( self::request('post', 'ndd/v2/api/MandateRequest/CreateEmandate', [
            'productId' => 1,
            'billerId' => 1,
            'accountNumber' => $input['accountNumber'],
            'bankCode' => $input['bankCode'],
            'payerName' => $input['payerName'],
            'mandateType' => 2,
            'payerAddress' => $input['payerAddress'],
            'accountName' => $input['accountName'],
            'amount' => $input['amount'],
            'frequency' => $input['frequency'],
            'narration' => $input['narration'] ?? 'e-mandate creation',
            'phoneNumber' => $input['phoneNumber'],
            'subscriberCode' => '12003074001',
            'startDate' => $input['startDate'],
            'endDate' => $input['endDate'] ?? Carbon::now()->addYears(5)->format('Y-m-d'),
            'payerEmail' => $input['payerEmail'],
         ]) );
    }
}
