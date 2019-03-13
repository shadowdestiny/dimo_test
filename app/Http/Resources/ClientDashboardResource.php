<?php

namespace App\Http\Resources;

use App\Client;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientDashboardResource extends JsonResource
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
            "uuid_client_info"          => $this->uuid_client_info,
            "first_name"                => $this->first_name,
            "last_name"                 => $this->last_name,
            "birth_date"                => $this->birth_date,
            "gender"                    => $this->gender,
            "dui"                       => $this->dui,
            "address"                   => $this->address,
            "city_id"                   => $this->city_id,
            "email"                     => $this->email,
            "alternative_number_phone"  => $this->alternative_number_phone,
            "clients_info_created_at"   => $this->clients_info_created_at,
            "clients_info_updated_at"   => $this->clients_info_updated_at,

            "client_uuid"               => $this->client_uuid,
            "uuid"                      => $this->uuid,
            "number_phone"              => $this->number_phone,
            "verified"                  => $this->verified,
            "level_uuid"                => $this->level_uuid,
            "status"                    => $this->status,
            "status_description"        => Client::STATUS_VALUE[$this->status],
            "invitation_code"           => $this->invitation_code,
            "comment"                   => $this->comment,
            "client_created_at"         => $this->client_created_at,
            "client_updated_at"         => $this->client_updated_at,

            "name"                      => $this->name,
        ];
    }
}
