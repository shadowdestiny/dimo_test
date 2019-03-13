<?php

namespace App\Http\Resources;

use App\Client;
use App\Level;
use App\Loan;
use App\Setting;
use Illuminate\Http\Resources\Json\JsonResource;

class LevelResource extends JsonResource
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
        $max_amounts = [];

        if ($request->get('client_uuid')) {
            $client = Client::whereUuid($request->get('client_uuid'))->with('loans')->first();

            foreach ($client->loans->where('status', Loan::PAID) as $loan) {
                array_push($max_amounts, $loan->amount->amount);
            }
        }

        $max_amount = $max_amounts ? collect($max_amounts)->max() : $this->amounts->min('amount');
        $levels     = Level::where('uuid', '!=', $this->uuid)->get();

        $index = 0;
        foreach ($this->amounts as $key => $amount) {
            if ($max_amount >= $amount['amount']) {
                $amount['available'] = 1;
                $index               = $key;
            } else {
                $amount['available'] = 0;
            }
        }

        if ($this->amounts[$index + 1]) {
            if (count($max_amounts)) {
                $this->amounts[$index + 1]['available'] = 1;
            }
        }

        $setting = Setting::where('key', '=', Setting::ANNUAL_RATE)->first();

        return [
            'uuid'                  => $this->uuid,
            'name'                  => $this->name,
            'key'                   => $this->key,
            'amounts'               => $this->amounts,
            'others'                => $levels,
            'next_level_amount'     => $this->next_level_amount,
            'annual_interest_rate'  => $setting->value,
            'next_level_steps'      => $this->next_level_steps,
            'max_loans'             => $this->max_loans,
            'max_time'              => $this->max_time,
            'commission'            => $this->commission,
            'has_30'            => $this->has_30,
        ];
    }
}
