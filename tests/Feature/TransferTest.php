<?php

use App\Actions\AccessGateway\GenerateAccessGatewayAction;
use App\Enums\TransactionEnum;
use App\Models\Recipients\Recipient;

describe('TransferTest', function () {
   it('That transfer is initiated', function () {
        $token  = (new GenerateAccessGatewayAction)->execute();

        $response = (object) $this->withHeaders([
            'Authorization' => 'Bearer ' . $token['token']
        ])->post('/transfer/initiate', [
            'name' => 'Tolu Robert',
            'accountNumber' => '0000000000',
            'bankCode' => '057',
            'amountInKobo' => '1000',
        ])->json();

        expect($response->status)->toBe('200');

        expect($response->data['reference'])->toBeString();

        expect(Recipient::count())->toBe(1);
    });

    it('That transfer is initiated and finalized', function () {
        $token  = (new GenerateAccessGatewayAction)->execute();
        $response = (object) $this->withHeaders([
            'Authorization' => 'Bearer ' . $token['token']
        ])->post('/transfer/initiate', [
            'name' => 'Tolu Robert',
            'accountNumber' => '0000000000',
            'bankCode' => '057',
            'amountInKobo' => '1000',
            'description' => 'my reason'
        ])->json();

        expect($response->status)->toBe('200');

        if ( $response->data['type'] == TransactionEnum::TYPE['transfer'] ) return;

        $response = (object) $this->withHeaders([
            'Authorization' => 'Bearer ' . $token['token']
        ])->post('/transfer/finalize', [
            'reference' => $response->data['reference'],
            'otp' => '0000000000',
        ])->json();

        expect($response->data['reference'])->toBeString();

        expect(Recipient::count())->toBe(1);
    });

    it('That transfer recipient is created', function () {
        $token  = (new GenerateAccessGatewayAction)->execute();

        $response = (object) $this->withHeaders([
            'Authorization' => 'Bearer ' . $token['token']
        ])->post('/transfer/create-recipient', [
            'name' => 'Tolu Robert',
            'accountNumber' => '0000000000',
            'bankCode' => '057',
        ])->json();

        expect($response->status)->toBe('200');

        expect(Recipient::count())->toBe(1);
    });

    it('That transfer is initiated via recipient', function () {
        $token  = (new GenerateAccessGatewayAction)->execute();

        $response = (object) $this->withHeaders([
            'Authorization' => 'Bearer ' . $token['token']
        ])->post('/transfer/create-recipient', [
            'name' => 'Tolu Robert',
            'accountNumber' => '0000000000',
            'bankCode' => '057',
        ])->json();

        expect($response->status)->toBe('200');
        
        $response = (object) $this->withHeaders([
            'Authorization' => 'Bearer ' . $token['token']
        ])->post('/transfer/initiate-via-recipient', [
            'amountInKobo' => '1000',
            'recipientCode' => $response->data['identifier'],
        ])->json();

        expect($response->status)->toBe('200');

        expect($response->data['reference'])->toBeString();

        expect(Recipient::count())->toBe(1);
    });
});

