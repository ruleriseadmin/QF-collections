<?php

namespace App\Http\Controllers\GSI;

use App\Supports\ApiReturnResponse;
use App\Http\Controllers\BaseController;
use App\Http\Resources\CustomerResource;
use App\Http\Requests\GSI\InitiateGSIRequest;
use App\Actions\GSI\MonoProfileCustomerAction;
use App\Http\Requests\GSI\MonoProfileCustomerRequest;

class GSIController extends BaseController
{
    public function initiateGSI(InitiateGSIRequest $request)
    {

    }

    //Used Specially for MONO Integration
    public function monoProfileCustomer(MonoProfileCustomerRequest $request)
    {
        $customer = (new MonoProfileCustomerAction)->execute($this->accessGateway, $request->validated());

        $customer['status'] == 'success' && $customer['data']['withMonoId'] = true;

        return $customer['status'] == 'success'
            ? ApiReturnResponse::success(new CustomerResource($customer['data']))
            : ApiReturnResponse::failed($customer['reason']);
    }
}
