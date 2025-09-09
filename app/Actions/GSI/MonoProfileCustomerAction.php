<?php

namespace App\Actions\GSI;

use App\Models\Gateway\AccessGateway;
use App\Service\Mono\MonoService;
use App\Traits\CustomerTrait;

class MonoProfileCustomerAction
{
    use CustomerTrait;

    public function execute(AccessGateway $accessGateway, array $input)
    {
        $customer = $this->retrieveCustomer($accessGateway, $input['email']);

        if ( $customer->mono_id ) return [
            'status' => 'success',
            'data' => $customer,
        ];

        $input['phone'] = $input['phoneNumber'];

        $input['type'] = 'individual';

        $createCustomer = MonoService::createCustomer(collect($input)->except([
            'phoneNumber'
        ])->toArray());

        if ( $createCustomer['success'] ){
            $customer->update([
                'mono_id' => $createCustomer['request']['id'],
                'phone_numbers' => collect($customer->phone_numbers)->push($createCustomer['request']['phone'])->unique(),
                'address' => $customer->address ?? $createCustomer['request']['address'],
                'first_name' => $customer->address ?? $createCustomer['request']['first_name'],
                'last_name' => $customer->address ?? $createCustomer['request']['last_name'],
            ]);
            return [
                'status' => 'success',
                'data' => $customer->refresh(),
            ];
        }

        if ( $createCustomer['existingId'] ){
            $customer->update([
                'mono_id' => $createCustomer['existingId'],
            ]);

            return [
                'status' => 'success',
                'data' => $customer->refresh(),
            ];
        }

        return [
            'status' => 'failed',
            'reason' => $createCustomer['message'],
        ];
    }
}
