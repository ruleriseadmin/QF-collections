<?php

use App\Actions\AccessGateway\GenerateAccessGatewayAction;

describe('VirtualAccountTest', function () {
   it('That Virtual Account is generated', function () {
        $token  = (new GenerateAccessGatewayAction)->execute();

        $response = (object) $this->withHeaders([
            'Authorization' => 'Bearer ' . $token['token']
        ])->post('/create-virtual-account', [
             'email' => 'test@example.com',
             'preferred_bank' => 'test-bank',
             'first_name' => 'john',
             //'middle_name' => 'john',
             'last_name' => 'doe',
             'phone' => '08012345678',
             'account_number' => '1234567890',
             'bvn' => '12345678901',
             'bank_code'=> '044'
        ])->json();

        dd($response);

        expect($response->status)->toBe('200');
    });
});

