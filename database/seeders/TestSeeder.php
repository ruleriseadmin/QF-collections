<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use App\Service\Mono\MonoService;
use App\Service\NIBSS\NibssService;
use App\Service\NIBSS\NibssICADService;
use App\Service\PayStack\PaystackService;
use App\Service\PayStack\PayStackDVAService;
use App\Service\PayStack\PayStackPaymentLink;
use App\Service\PayStack\PayStackDirectDebitService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Actions\AccessGateway\GenerateAccessGatewayAction;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // {{
        //     "productId": 1,
        //     "billerId": 1,
        //     "accountNumber": "2222222222",
        //     "bankCode": "058",
        //     "payerName": "Alex lopez",
        //     "mandateType": 2,
        //     "payerAddress": "maryland Ikeja computer village",
        //     "accountName": "Micheal lopez",
        //     "amount": 100000,
        //     "frequency": 48,
        //     "narration": "test e mandate response",
        //     "phoneNumber": "08028134486",
        //     "subscriberCode": "12003074001",
        //     "startDate": "2024-11-12",
        //     "endDate": "2024-11-13",
        //     "payerEmail": "olumayokunolayinka@gmail.com"
        //   }}

       // dd(PayStackDirectDebitService::verifyAuthCode('ckipdei9zr5nw8z'));

        // dd(MonoService::createCustomer([
        //     'identity' => [
        //         'type' => 'nin',
        //         'number' => '75654310047',
        //     ],
        //     'lastName' => 'Olayinka',
        //     'firstName' => 'Olumayokun',
        //     'address' => 'Lagos, Nigeria',
        //     //'phoneNumber' => '2349131864392',
        //     'email' => 'olumayokunolayinka@gmail.com',
        // ]));

        //6762d0abe215dd6a14fa18ce

       dd( MonoService::initiateGSM([
            'reference' => str()->uuid(),
            'amount' => 100000,
            'description' => 'test',
            'number' => '08100000000',
            'callbackUrl' => 'http://collection-service-backend.test',
            'customerId' => '6762d0abe215dd6a14fa18ce',
            'startDate' => Carbon::now()->format('Y-m-d'),
            'endDate' => Carbon::now()->addYears(5)->format('Y-m-d'),
       ]) );

        NibssICADService::createSingleAccount([]);
        dd();

        dd( NibssService::createEMandate([
            'accountNumber' => '2222222222',
            'bankCode' => '058',
            'payerName' => 'Alex lopez',
            'payerAddress' => 'maryland Ikeja computer village',
            'accountName' => 'Micheal lopez',
            'amount' => 100000,
            'frequency' => 48,
            'narration' => 'test e mandate response',
            'phoneNumber' => '08028134486',
            'startDate' => '2024-11-18',
            'endDate' => '2025-11-18',
            'payerEmail' => 'olumayokunolayinka@gmail.com'
        ]) );
        dd((new GenerateAccessGatewayAction)->execute());
        //PayStackDirectDebitService::initialize('olumayokun.o@simbrellang.com', 'http://collection-service-backend.test');

        dd(PayStackPaymentLink::createPaymentLinkForCard('2000', 'olumayokunolayink@gmail.com'));
        //PayStackDVAService::createVirtualAccount(str()->uuid(), 'wema-bank');
    }
}
