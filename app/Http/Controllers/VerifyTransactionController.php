<?php

namespace App\Http\Controllers;

use App\Supports\ApiReturnResponse;
use App\Actions\Transaction\VerifyTransactionAction;
use Illuminate\Http\Request;

class VerifyTransactionController extends BaseController
{
    public function __invoke(string $reference, Request $request)
    {
        $directViaPayStack = $request->filled('directViaPayStack') ? $request->input('directViaPayStack') : false;

        return ApiReturnResponse::success((new VerifyTransactionAction)
            ->execute($this->accessGateway, $reference, $directViaPayStack));
    }
}
