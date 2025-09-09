<?php

namespace App\Service\PayStack;

use Exception;
use Illuminate\Support\Facades\Log;

class PayStackTransferService extends PaystackService
{
    public static function createRecipient(string $name, string $accountNumber, string $bankCode): array
    {
        $request = static::request('post', 'transferrecipient', [
            'type' => 'nuban',
            'name' => $name,
            'account_number' => $accountNumber,
            'bank_code' => $bankCode,
            'currency' => 'NGN',
        ]);

        if ( ! $request['status'] ) {
            Log::error(collect($request));
            throw new Exception($request['message'] ?? 'Error from PayStack on create recipient.');
        }

        return [
            'success' => true,
            'request' => $request['data'],
            'identifier' => $request['data']['recipient_code'],
        ];
    }

    public static function initiateTransfer(string $amountInKobo, string $recipientCode, string $description, ?string $reference = null): array
    {
        $data = [
            'source' => 'balance',
            'amount' => $amountInKobo,
            'recipient' => $recipientCode,
            'reason' => $description,
        ];

        if ($reference) {
            $data['reference'] = $reference;
        }
        
        $request = static::request('post', 'transfer', $data);

        if ( ! $request['status'] ) {
            Log::error(collect($request));
            throw new Exception($request['message'] ?? 'Error from PayStack on transfer.');
        }

        $isOtpEnabled = $request['data']['status'] == 'otp';

        return [
            'success' => true,
            'request' => $request['data'],
            'reference' => $isOtpEnabled ? $request['data']['transfer_code'] : $request['data']['reference'],
            'status' => $request['data']['status'],
            'isOtpEnabled' => $isOtpEnabled,
        ];
    }

    public static function finalizeTransfer(string $reference, string $otp): array
    {
        $request = static::request('post', 'transfer/finalize_transfer', [
            'transfer_code' => $reference,
            'otp' => $otp,
        ]);

        if ( ! $request['status'] ) {
            Log::error(collect($request));
            throw new Exception($request['message'] ?? 'Error from PayStack on finalize transfer.');
        }

        return [
            'success' => true,
            'request' => $request['data'],
            'reference' => $request['data']['reference'],
            'status' => $request['data']['status']
        ];
    }

    public static function verifyTransfer(string $reference): array
    {
        $request = static::request('get', "transfer/verify/$reference");

        if ( ! $request['status']  ) {
            Log::error(collect($request));
            return [
                'active' => false,
                'message' => $request['message'],
                'meta' => null,
            ];
        }

        return [
            'active' => strtolower($request['data']['status']) == 'success',
            'message' => $request['message'],
            'meta' => $request['data'],
        ];
    }
}
