<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LevelAmountResource extends JsonResource
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
            'uuid'          => $this->uuid,
            'level_uuid'    => $this->level_uuid,
            'amount'        => $this->amount,
            'has_30'        => $this->has_30,
            'available'     => $this->available === true ? "true" : "false",
            'created_at'     => $this->created_at->format('d-m-Y'),
        ];
    }
}
