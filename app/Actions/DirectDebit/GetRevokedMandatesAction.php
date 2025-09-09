<?php

namespace App\Actions\DirectDebit;

use App\Service\PayStack\PayStackDirectDebitService;
use Exception;
use App\Models\Gateway\AccessGateway;
use App\Models\DirectDebit\RevokedMandate;
use Illuminate\Support\Facades\Log;

class GetRevokedMandatesAction
{
    public function execute(AccessGateway $accessGateway)
    {
        try{
            $revokedMandates = PayStackDirectDebitService::getRevokedMandates();

            // save the revoked mandate to the database
            foreach ($revokedMandates as $mandate) {

                // Assuming you have a Mandate model to save the data
                $accessGateway->revokedMandates()->updateOrCreate(
                    ['mandate_code' => $mandate['authorization_code']],
                    [
                        'customer_code' => $mandate['customer']['customer_code'] ?? null,
                        'email' => $mandate['customer']['email'] ?? null,
                        'status' => $mandate['status'],
                        'revoked_at' => $mandate['authorized_at'] ?? null,
                        'meta' => $mandate ?? []
                    ]
                );

            }

            return $revokedMandates;
        }catch(Exception $ex){
            Log::error('Error @ GetRevokedMandatesAction: ' . $ex->getMessage());
            return null;
        }
    }
}
