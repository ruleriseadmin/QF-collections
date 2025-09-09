<?php

namespace App\Actions\Customer;

use App\Models\Customer\Customer;
use App\Models\Gateway\AccessGateway;
use Exception;

class CreateCustomerAction
{
    public function execute(AccessGateway $accessGateway, array $input): Customer
    {
        try {
            return  $accessGateway->customers()->create($input);
        } catch (Exception $ex) {
            throw new Exception('Error @ CreateCustomerAction: ' . $ex->getMessage());
        }
    }
}
