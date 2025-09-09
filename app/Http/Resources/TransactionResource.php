<?php

namespace App\Http\Resources;

use App\Enums\TransactionEnum;
use App\Traits\TransactionResourceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class TransactionResource extends JsonResource
{
    use TransactionResourceTrait;

    public function toArray(Request $request): array
    {
        $response = collect([
                'reference' => $this->provider_reference ?? $this->reference,
                'type' => $this->type,
                'status' => $this->status,
                //'amount' => $this->amount ?? 0,
                'customer' => $this->customer ? [
                'email' => $this->customer?->email
                ] : null,
        ]);

        if ( $this->amount ){
            $response->put('amount', $this->amount);
        }

       match($this->type){
        TransactionEnum::TYPE['direct_debit'] => $response = $response->merge($this->directDebitResponse($this->meta)),
        TransactionEnum::TYPE['card_tokenization'] => $response = $response->merge($this->cardTokenizationResponse($this->meta)),
        TransactionEnum::TYPE['account_charge'], TransactionEnum::TYPE['partial_payment'] => $response = $response->merge($this->accountCharge($this->meta)),
        //in_array($this->type, [TransactionEnum::TYPE['account_charge'], TransactionEnum::TYPE['partial_payment']]) => $response = $response->merge($this->accountCharge($this->meta)),
        default => null,
       };

    //    if ( Arr::has($this->meta, 'request.amount') ){
    //        $response->put('amount', $this->meta['request']['amount']);
    //    }

        return $response->toArray();
    }
}