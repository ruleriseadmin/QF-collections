<?php

use App\Actions\AccessGateway\GenerateAccessGatewayAction;

describe('GSITest', function () {
   it('Mono customer is created successfully', function () {
        $token  = (new GenerateAccessGatewayAction)->execute();

        $response = (object) $this->withHeaders([
            'Authorization' => 'Bearer ' . $token['token']
        ])->post('/gsi/profile-customer', [
            'identity' => [
                'type' => 'nin',
                'number' => '72232218030',
            ],
            'email' => 'mono@gmail.com',
            'type' => 'individual',
            'lastName' => 'Customer',
            'firstName' => 'Mono',
            'address' => 'Some Address',
            'phoneNumber' => '09131864392',
        ])->json();

        dd($response);

        expect($response->status)->toBe('200');
    });
});

