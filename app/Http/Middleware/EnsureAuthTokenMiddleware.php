<?php

namespace App\Http\Middleware;

use App\Models\AuthToken;
use App\Models\Gateway\AccessGatewayToken;
use Closure;
use Illuminate\Http\Request;
use App\Supports\ApiReturnResponse;
use Symfony\Component\HttpFoundation\Response;

class EnsureAuthTokenMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (empty($token)) {
            return ApiReturnResponse::unAuthorized();
        }
        $token = AccessGatewayToken::retrieveToken($token);

        if ( $token ){
            $request['accessGateway'] = $token->accessGateway;
        }

        return $token ? $next($request) : ApiReturnResponse::unAuthorized();
    }
}
