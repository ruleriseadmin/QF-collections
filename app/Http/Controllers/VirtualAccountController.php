<?php

namespace App\Http\Controllers;

use App\Actions\VirtualAccount\CreateVirtualAccountAction;
use App\Http\Requests\Customer\CreateVirtualAccountRequest;
use App\Supports\ApiReturnResponse;

class VirtualAccountController extends BaseController
{
    public function createVirtualAccount(CreateVirtualAccountRequest $request)
    {
        $account = (new CreateVirtualAccountAction)->execute($this->accessGateway, $request->validated());

        return $account['status'] ? ApiReturnResponse::success() : ApiReturnResponse::failed($account['message']);
    }
}
