<?php

namespace App\Actions\Transfer;

use Exception;
use App\Enums\TransactionEnum;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Gateway\AccessGateway;
use App\Service\PayStack\PayStackTransferService;
use App\Supports\ApiReturnResponse;

class FinalizeTransferAction
{
    public function execute(AccessGateway $accessGateway, array $input)
    {
        DB::beginTransaction();
        try{
            $request = (object) PayStackTransferService::finalizeTransfer(
                reference: $input['reference'],
                otp: $input['otp'],
            );

            $transaction = $accessGateway->tempTransactions()->create([
                'reference' => $request->reference,
                'type' => TransactionEnum::TYPE['transfer'],
                'amount' => $request['request']['amount'],
                'provider' => TransactionEnum::PROVIDERS['paystack'],
                'status' => strtolower($request->status) == 'success' ? TransactionEnum::STATUS['success'] : TransactionEnum::STATUS['failed'],
                'meta' => $request,
            ]);

            $accessGateway->tempTransactions()->where('reference', $input['reference'])->delete();
            DB::commit();
            return $transaction;
        }catch(Exception $ex){
            DB::rollBack();
            Log::error('Error @ FinalizeTransferAction: ' . $ex->getMessage());
            throw new ApiException('Error @ FinalizeTransferAction: ' . $ex->getMessage());
        }
    }
}
