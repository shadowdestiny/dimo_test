<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StepResource extends JsonResource
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
            'uuid'      => $this->uuid,
            'name'      => $this->name,
            'order'     => $this->order,
            'questions' => QuestionResource::collection($this->questions),
        ];
    }
}
