<?php

namespace App;

use App\Traits\HasUuid;

class LoanDetail extends BaseModel
{
    use HasUuid;

    const ACTIVE = '1';
    const PAID   = '2';
    const DUE    = '3';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'loan_uuid',
        'number_fee',
        'capital',
        'commission',
        'balance',
        'payday',
        'fee',
        'interest',
        'balance_total',
        'balance_debt',
        'balance_total_debt',
        'debt',
        'status',
    ];

    protected $dates = [
        'payday',
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
