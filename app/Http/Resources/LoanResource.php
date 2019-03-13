<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'uuid'        => $this->uuid,
            'client_uuid' => $this->client_uuid,
            'amount'      => $this->amount,
            'status'      => $this->status,
            'comment'     => $this->comment,
            'detail'      => $this->detail,
            'wallet_uuid' => $this->wallet_uuid,
        ];
    }
}
