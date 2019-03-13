<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
        $info    = collect($this->info);
        $amounts = \DB::table('loans')->where('loans.client_uuid', $this->uuid)
                               ->join('level_amounts', 'loans.level_amount_uuid', '=', 'level_amounts.uuid')
                               ->where('level_amounts.level_uuid', $this->level_uuid)
                               ->groupBy('level_amounts.uuid')
                               ->select('level_amounts.amount as amount', \DB::raw('count(*) as count'))
                               ->get();

        return [
            'number_phone' => $this->number_phone,
            'verified'     => $this->verified,
            'status'       => $this->status,
            'level'        => collect($this->level)->merge(['amounts' =>$amounts]),
            $this->merge(
                $info->except(['uuid', 'created_at', 'updated_at'])
            ),
        ];
    }
}
