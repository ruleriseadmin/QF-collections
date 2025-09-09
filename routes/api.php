<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GSI\GSIController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\DirectDebitController;
use App\Http\Controllers\PaymentLinksController;
use App\Http\Controllers\ChargeAccountController;
use App\Http\Controllers\VirtualAccountController;
use App\Http\Controllers\CardTokenizationsController;
use App\Http\Controllers\VerifyTransactionController;
use App\Http\Controllers\Transfer\TransfersController;
use App\Http\Controllers\AccessGateway\WebhookController;
use App\Http\Controllers\Webhook\WebhookController as WebhookWebhookController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('webhook/paystack', [WebhookWebhookController::class, 'handleWebhook']);

Route::middleware(['ensure-auth-token'])->group(function(){
    Route::prefix('payment-links')->group(function(){
        Route::post('initiate', [PaymentLinksController::class, 'generatePaymentLink']);
    });

    Route::post('create-virtual-account', [VirtualAccountController::class, 'createVirtualAccount']);

    Route::prefix('direct-debit')->group(function(){
        Route::post('initiate', [DirectDebitController::class, 'initiate']);
        Route::get('verify/{reference}', [DirectDebitController::class, 'verifyDirectDebit']);
        Route::get('revoked-mandates', [DirectDebitController::class, 'getRevokedMandates']);
    });

    Route::prefix('card-tokenization')->group(function(){
        Route::post('initiate', [CardTokenizationsController::class, 'initiateCardTokenization']);
    });

    Route::prefix('transaction')->group(function(){
        Route::post('charge-account', [ChargeAccountController::class, 'chargeAccount']);
        Route::post('partial-payment', [PaymentsController::class, 'partialPayment']);
        Route::get('verify/{reference}', [VerifyTransactionController::class, '__invoke']);
    });

    Route::prefix('settings')->group(function(){
        Route::post('update-webhook-url', [WebhookController::class, 'updateWebhookUrl']);
    });

    Route::prefix('transfer')->group(function(){
        Route::post('initiate', [TransfersController::class, 'initiateTransfer']);
        Route::post('finalize', [TransfersController::class, 'finalizeTransfer']);
        Route::post('create-recipient', [TransfersController::class, 'createTransferRecipient']);
        Route::post('initiate-via-recipient', [TransfersController::class, 'initiateTransferViaRecipientCode']);
    });

    Route::prefix('gsi')->group(function(){
        Route::post('profile-customer', [GSIController::class, 'monoProfileCustomer']);
    });
});

Route::prefix('')->group(function(){});
