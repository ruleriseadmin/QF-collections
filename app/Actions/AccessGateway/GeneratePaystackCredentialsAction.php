<?php

namespace App\Actions\AccessGateway;

use App\Models\Gateway\AccessGateway;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GeneratePaystackCredentialsAction
{
    protected ?AccessGateway $accessGateway;

    public function execute(): ?array
    {
        DB::beginTransaction();
        try {
            $request = request();

            if ($request->accessGateway) {
                $this->accessGateway = $request->accessGateway;
            }

            $paystackPublicKey = '';
            $paystackSecretKey = '';

            switch ($this->accessGateway->name) {
                case 'quickcred':
                    $paystackPublicKey = config('service.paystack.quickcred.public_key');
                    $paystackSecretKey = config('service.paystack.quickcred.secret_key');
                    break;
                case 'quickfund':
                    $paystackPublicKey = config('service.paystack.quickfund.public_key');
                    $paystackSecretKey = config('service.paystack.quickfund.secret_key');
                    break;
                default:
                    $paystackPublicKey = config('service.paystack.quickcred.public_key');
                    $paystackSecretKey = config('service.paystack.quickcred.secret_key');
                    break;
            }

            DB::commit();

            return [
                'paystack_public_key' => $paystackPublicKey,
                'paystack_secret_key' => $paystackSecretKey,
            ];
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error('Error @ GeneratePaystackCredentialsAction: ' . $ex->getMessage());
            return null;
        }
    }
}
