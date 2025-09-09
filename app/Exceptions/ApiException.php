<?php

namespace App\Exceptions;

use Exception;
use App\Supports\ApiReturnResponse;
use Illuminate\Http\JsonResponse;

class ApiException extends Exception
{
        public function report(): ?bool
    {
        return false;
    }

    public function render(): JsonResponse
    {
        return ApiReturnResponse::failed($this->getMessage());
    }
}
