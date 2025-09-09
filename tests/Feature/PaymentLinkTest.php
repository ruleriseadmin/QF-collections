<?php

use App\Actions\AccessGateway\GenerateAccessGatewayAction;

describe('PaymentLinkTest', function () {
   it('That payment link is generated', function () {
       $token  = (new GenerateAccessGatewayAction)->execute();
       $response = (object) $this->withHeaders([
           'Authorization' => 'Bearer ' . $token['token']
       ])->post('/payment-links/initiate', [
            'email' => 'test@example.com',
            'amount' => '500000', //amount in kobo
       ])->json();

       expect($response->data['reference'])->toBeString();
    });

    it('That payment link is generated for card tokenization', function () {
        $token  = (new GenerateAccessGatewayAction)->execute();
        $response = (object) $this->withHeaders([
            'Authorization' => 'Bearer ' . $token['token']
        ])->post('/card-tokenization/initiate', [
             'email' => 'test@example.com',
        ])->json();

        expect($response->data['reference'])->toBeString();
     });

});

