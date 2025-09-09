<?php

namespace App\Http\Middleware\Webhook;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class PayStackVerifyWebhookSignature
{
    public function handle($request, Closure $next)
    {
        // validate that callback is coming from Paystack
        if (strtolower($request->method()) !== 'post' || ! $request->header('x-paystack-signature', null)) {
            throw new AccessDeniedHttpException("Invalid Request");
        }

        $input = $request->getContent();

        // $paystackKey = config('paystackwebhooks.secret', env('PAYSTACK_SECRET'));

        // if ($request->header('x-paystack-signature') !== hash_hmac('sha512', $input, $paystackKey)) {
        //     throw new AccessDeniedHttpException("Access Denied");
        // }

        $paystackKeys = [
            'quickcred' => config('paystackwebhooks.quickcred_secret', env('PAYSTACK_QUICKCRED_SECRET_KEY')),
            'quickfund' => config('paystackwebhooks.quickfund_secret', env('PAYSTACK_QUICKFUND_SECRET_KEY')),
        ];

        $validKey = null;

        foreach ($paystackKeys as $keyName => $paystackKey) {
            if ($request->header('x-paystack-signature') === hash_hmac('sha512', $input, $paystackKey)) {
                $validKey = $keyName;
                break;
            }
        }

        if (!$validKey) {
            throw new AccessDeniedHttpException("Access Denied");
        }

        // Add the valid key to the request for further processing
        $request->merge([
            'paystack_source' => $validKey
        ]);

        return $next($request);
    }
}
