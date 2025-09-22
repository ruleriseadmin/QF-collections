<?php

namespace App\Actions\Transaction;

use App\Enums\TransactionEnum;
use App\Http\Resources\TransactionResource;
use App\Models\Gateway\AccessGateway;
use App\Service\PayStack\PaystackService;
use App\Service\PayStack\PayStackTransferService;
use KodeDict\PHPUtil\PhpUtil;
use Illuminate\Support\Facades\Log;

class VerifyTransactionAction
{
    public function execute(AccessGateway $accessGateway, string $reference, bool $directViaPayStack = false)
    {
        $transaction = $accessGateway->transactions()->where('reference', $reference)
            ->orWhere('provider_reference', $reference)->first();


        if ( $transaction && ! $directViaPayStack ){
            Log::info('Transaction already exists and directViaPayStack is false. Returning existing transaction.', [
                'transaction_id' => $transaction->id,
                'reference' => $transaction->reference,
                'provider_reference' => $transaction->provider_reference,
            ]);
            Log::info('Transaction Meta', [
                'meta' => new TransactionResource($transaction)
            ]);
            return [
                'status' => true,
                'metadata' => new TransactionResource($transaction),
                'message' => 'transaction processed successfully',
            ];
        }

        if ( $transaction && $directViaPayStack ){
            
            return $this->directViaPayStackByTransaction($transaction);
        }

        $tempTransaction = $accessGateway->tempTransactions()->where('reference', $reference)->first();

        if ( ! $tempTransaction ){
            return [
                'status' => false,
                'message' => 'transaction not found',
                'metadata' => null,
            ];
        }

        $verifyRequest = match($tempTransaction->type){
            TransactionEnum::TYPE['transfer'] => $this->verifyTransferTransaction($tempTransaction->reference),
            TransactionEnum::TYPE['direct_debit'] => $this->verifyDirectDebitTransaction($tempTransaction->reference),
            default => $this->verifyDefaultTransaction($tempTransaction->reference),
        };

        if ( ! $verifyRequest['active'] ){
            return [
                'status' => false,
                'message' => $verifyRequest['message'] ?? null,
                'metadata' => null,
            ];
        }

        $transaction = $accessGateway->transactions()->create([
            'reference' => PhpUtil::generateUniqueReference('16', 'trx-'),
            'provider_reference' => $reference,
            'provider' => $tempTransaction->provider,
            'amount' => $tempTransaction->amount,
            'status' => TransactionEnum::STATUS['success'],
            'customer_id' => $tempTransaction->customer_id,
            'type' => $tempTransaction->type,
            'meta' => $verifyRequest['meta'],
        ]);

        return [
            'status' => true,
            'metadata' => new TransactionResource($transaction),
            'message' => 'transaction processed successfully',
        ];
    }

    private function directViaPayStackByTransaction($transaction): array
    {
        $verifyRequest = match($transaction->type){
            TransactionEnum::TYPE['transfer'] => $this->verifyTransferTransaction($transaction->provider_reference),
            TransactionEnum::TYPE['direct_debit'] => $this->verifyDirectDebitTransaction($transaction->provider_reference),
            default => $this->verifyDefaultTransaction($transaction->provider_reference),
        };

        if ( ! $verifyRequest['active'] ){
            return [
                'status' => false,
                'message' => $verifyRequest['message'] ?? null,
                'metadata' => null,
            ];
        }

        // $transaction->update([
        //     'status' => TransactionEnum::STATUS['success'],
        //     'meta' => $verifyRequest['meta'],
        // ]);

        return [
            'status' => true,
            'metadata' => new TransactionResource($transaction),
            'message' => 'transaction processed successfully',
        ];
    }

    private function verifyDefaultTransaction(string $reference)
    {
        return PaystackService::verifyTransaction($reference);
    }

    private function verifyTransferTransaction(string $reference)
    {
        return PayStackTransferService::verifyTransfer($reference);
    }

    private function verifyDirectDebitTransaction(string $reference)
    {
        return PaystackService::verifyAuthCode($reference);
    }
}
