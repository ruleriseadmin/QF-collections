<?php

namespace App\Http\Resources;

use App\Supports\HelperSupport;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $response = collect(parent::toArray($request))->except([
           'id',
           'created_at',
           'updated_at',
           'deleted_at',
           'mono_id',
           'uuid',
        ]);

        if ( $this->withMonoId ?? false ){
            $response['gsi_id'] = $this->mono_id;
            $response = $response->except('withMonoId');
        }

        return HelperSupport::snake_to_camel($response->toArray());
    }
}
