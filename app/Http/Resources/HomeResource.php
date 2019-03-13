<?php

namespace App\Http\Resources;

use App\Level;
use App\Loan;
use App\WalletBrand;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeResource extends JsonResource
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
        $amounts = [
            'silver_min'  => Level::where('key', 'silver')->first()->amounts()->where('available', true)->min('amount'),
            'silver_max'  => Level::where('key', 'silver')->first()->amounts()->where('available', true)->max('amount'),
            'gold_min'    => Level::where('key', 'gold')->first()->amounts()->where('available', true)->min('amount'),
            'gold_max'    => Level::where('key', 'gold')->first()->amounts()->where('available', true)->max('amount'),
            'diamond_min' => Level::where('key', 'diamond')->first()->amounts()->where('available', true)->min('amount'),
            'diamond_max' => Level::where('key', 'diamond')->first()->amounts()->where('available', true)->max('amount'),
        ];

        $max_amounts = [];

        foreach ($this->loans->where('status', Loan::PAID) as $loan) {
            array_push($max_amounts, $loan->amount->amount);
        }

        return [
            'client_uuid'     => $this->uuid,
            'number_phone'    => $this->number_phone,
            'verified'        => $this->verified,
            'status'          => $this->status,
            'wallet_uuid'     => WalletBrand::where('name', 'Tigo Money')->first()->uuid,
            'level'           => collect($this->level)->merge([
                                    'max_amount' => money_format('%i', collect($max_amounts)->max()),
                                    'amounts'    => $amounts,
                                ]),
            'invitation_code' => $this->invitation_code,
            'data'            => $this->when(in_array($this->status, ['approved', 'active', 'completed', 'active_due']), function () {
                switch ($this->status) {
                    case 'approved':
                        return $this->loanApproved();
                    case 'active':
                        return $this->loanActive();
                    case 'active_due':
                        return $this->loanActiveDue();
                    case 'completed':
                        return $this->loanComplete();
                }
            }),
        ];
    }
}
