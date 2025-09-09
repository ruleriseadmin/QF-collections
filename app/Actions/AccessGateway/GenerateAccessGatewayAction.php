<?php

namespace App\Actions\AccessGateway;

use App\Models\Gateway\AccessGateway;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use KodeDict\PHPUtil\PhpUtil;

class GenerateAccessGatewayAction
{
    public function execute(): ?array
    {
        DB::beginTransaction();
        try{
            $accessGateway = AccessGateway::create([
                // 'name' => PhpUtil::randomString(8),
                'name' => 'quickfund',
            ]);

            $token = PhpUtil::generateUniqueReference(24, 'sk_');

            $testToken = PhpUtil::generateUniqueReference(24, 'sk_test_');

            $accessGateway->accessToken()->create([
               'access_id' => rand(000000, 999999),
               'token' => Crypt::encrypt($token),
               'test_token' => Crypt::encrypt($testToken),
            ]);

            $accessGateway->webhookConfig()->create();
            DB::commit();

            return [
                'token' => $token,
                'testToken' => $testToken
            ];
        }catch(Exception $ex){
            DB::rollBack();
            Log::error('Error @ GenerateAccessGatewayAction: ' . $ex->getMessage());
            return null;
        }
    }
}
