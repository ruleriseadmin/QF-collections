<?php

use App\Actions\AccessGateway\GenerateAccessGatewayAction;

describe('PaymentTest', function () {
   it('does something', function () {
        $token  = (new GenerateAccessGatewayAction)->execute();
        $response = (object) $this->withHeaders([
            'Authorization' => 'Bearer ' . $token['token']
        ])->post('/transaction/partial-payment', [
            'email' => 'test@example.com',
            'amountInKobo' => '20000',
            'authorizationCode' => '',
        ])->json();

        dd($response);

        expect($response['status'])->toBe('200');
    });
});

