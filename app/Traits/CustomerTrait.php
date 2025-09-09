<?php

namespace App\Traits;

use App\Models\Customer\Customer;
use App\Actions\Customer\CreateCustomerAction;
use App\Models\Gateway\AccessGateway;

trait CustomerTrait
{
    protected function retrieveCustomer(AccessGateway $accessGateway, string $email): Customer
    {
        $customer = $accessGateway->customers()->whereEmail($email)->first();

        return $customer ? $customer : (new CreateCustomerAction)->execute($accessGateway, ['email' => $email]);
    }
}
