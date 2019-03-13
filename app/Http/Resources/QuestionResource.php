<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
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
            'uuid'     => $this->uuid,
            'text'     => $this->text,
            'type'     => $this->type,
            'required' => $this->required,
            'options'  => OptionResource::collection($this->options),
        ];
    }
}
