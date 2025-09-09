<?php

namespace App\Actions\VirtualAccount;

use App\Models\Gateway\AccessGateway;
use App\Models\VirtualAccountProcess;
use App\Service\PayStack\PayStackDVAService;
use App\Traits\CustomerTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateVirtualAccountAction
{
    use CustomerTrait;

    public function execute(AccessGateway $accessGateway, array $input)
    {
        $customer = $this->retrieveCustomer($accessGateway, $input['email']);

        $input['country'] = 'NG';

        DB::beginTransaction();
        try{
            $init = PayStackDVAService::createVirtualAccount($input);//['status']
            if ( ! $init['status'] ){
                return [
                    'status' => false,
                    'message' => $init['message']
                ];
            }
            VirtualAccountProcess::create([
                'customer_id' => $customer->id,
                'access_gateway_id' => $accessGateway->id,
            ]);
            DB::commit();
            return [
                'status' => true
            ];
        }catch(Exception $e){
            //dd($e->getMessage());
            DB::rollBack();
            Log::error('Error @ CreateVirtualAccountAction: ' . $e->getMessage());
            throw new Exception('Error @ CreateVirtualAccountAction: ' . $e->getMessage());
        }
    }
}
