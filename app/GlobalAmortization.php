<?php

namespace App;

use App\Traits\HasUuid;

class GlobalAmortization extends BaseModel
{
    use HasUuid;

    protected $table = 'global_amortization';

    protected $fillable = [
        'day',
        'balance',
        'interest',
        'commission',
        'pay',
        'comission_prepayment',
        'balance_total',
    ];

    /**
     * Get loan belongs to loan detail.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_uuid');
    }
}
