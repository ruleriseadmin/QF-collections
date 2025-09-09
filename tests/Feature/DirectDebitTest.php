<?php

use App\Actions\AccessGateway\GenerateAccessGatewayAction;
use App\Models\DirectDebit\TempDirectDebit;

describe('DirectDebitTest', function () {
   it('That Direct Debit is initiated', function () {
        $token  = (new GenerateAccessGatewayAction)->execute();
        $response = (object) $this->withHeaders([
            'Authorization' => 'Bearer ' . $token['token']
        ])->post('/direct-debit/initiate', [
             'email' => 'test@example.com',
             'callback_url' => 'https://www.google.com/',
             'account' => [
                'number' => '0123456789',
                'bank_code' => '044',
             ],
        ])->json();

        dd($response);

        expect($response->data['url'])->toBeString();

        //expect(TempDirectDebit::count())->toBe(1);
    });

    it('That Direct Debit is verified', function () {
        $token  = (new GenerateAccessGatewayAction)->execute();
        $response = (object) $this->withHeaders([
            'Authorization' => 'Bearer ' . $token['token']
        ])->get("/direct-debit/verify/ddd")->json();

        expect($response->status)->toBe('200');

        expect($response->data['active'])->toBeFalse();
    });
});

