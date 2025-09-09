<?php

use App\Supports\ApiReturnResponse;
use Illuminate\Foundation\Application;
use App\Http\Middleware\EnsureAuthTokenMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: '/',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'ensure-auth-token' => EnsureAuthTokenMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function(ValidationException $e, Request $request){
            return ApiReturnResponse::validationError($e->errors());
        });

        $exceptions->render(function(Throwable $e, Request $request){
            return ApiReturnResponse::failed();
        });

        $exceptions->render(function(HttpException $e, Request $request){
            return ApiReturnResponse::notFound();
        });

    })->create();
