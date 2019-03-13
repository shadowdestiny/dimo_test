<?php

namespace App;

use App\Traits\HasUuid;

class Client extends BaseModel
{
    use HasUuid;

    const BRONZE = '1';
    const SILVER = '2';
    const GOLD   = '3';

    const INITIAL          = 'initial';
    const AVAILABLE        = 'available';
    const IN_PROCESS       = 'in_process';
    const APPROVED         = 'approved';
    const ACTIVE           = 'active';
    const REJECTED         = 'rejected';
    const COMPLETED        = 'completed';
    const ARREARS          = 'active_due';

    const STATUS = [
        self::INITIAL,
        self::AVAILABLE,
        self::IN_PROCESS,
        self::APPROVED,
        self::ACTIVE,
        self::REJECTED,
        self::COMPLETED,
        self::ARREARS,
    ];

    const STATUS_VALUE = [
        self::INITIAL     => 'Inicial',
        self::AVAILABLE   => 'Disponible',
        self::IN_PROCESS  => 'En progreso',
        self::APPROVED    => 'Aprobado',
        self::ACTIVE      => 'Activo',
        self::REJECTED    => 'Rechazado',
        self::COMPLETED   => 'Completado',
        self::ARREARS     => 'En mora',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number_phone',
        'verified',
        'level_uuid',
        'comment',
        'status',
        'token',
        'start_date_level',
        'start_due_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start_date_level',
        'start_due_at',
    ];

    /**
     * Scope a query to get client with specific number phone.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $number
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsNewClient($query, $number)
    {
        $user = $query->where('number_phone', $number)->first();

        return $user ? false : true;
    }

    /**
     * Undocumented function.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function info()
    {
        return $this->hasOne(ClientInfo::class, 'client_uuid', 'uuid');
    }

    /**
     * Get all loans from client.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function loans()
    {
        return $this->hasMany(Loan::class, 'client_uuid', 'uuid');
    }

    /**
     * Get level belongs to client.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class, 'client_uuid', 'uuid');
    }

    /**
     * Get all wallets from client.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wallets()
    {
        return $this->hasMany(ClientWallet::class, 'client_uuid', 'uuid');
    }

    /**
     * Check Client with loan approved.
     */
    public function loanApproved()
    {
        $amounts = $this->loans()->with('amount')->whereStatus(Loan::PENDING)->first()->amount;
        $loan    = collect($this->loans()->first())->merge([
            'loan_approved_amount' => money_format('%i', $amounts->amount),
            'loan_approved_uuid'   => $this->loans()->whereStatus(Loan::PENDING)->first()->uuid,
        ]);

        return $loan;
    }

    /**
     * Check Client with loan active.
     */
    public function loanActive()
    {
        $amounts          = $this->loans()->with('amount')->whereStatus(Loan::ACCEPTED)->first()->amount;
        $detail           = $this->loans()->whereStatus(Loan::ACCEPTED)->first()->detail;
        $actual_quote     = $detail->where('status', LoanDetail::ACTIVE)->first();
        $last_quote       = 1 == $actual_quote->number_fee ? null : $detail->where('number_fee', $actual_quote->number_fee - 1)->first();
        $next_quote       = $detail->where('number_fee', $actual_quote->number_fee + 1)->first();
        $sum_total_amount = $detail->sum('fee');
        $loan             = $this->loans()->whereStatus(Loan::ACCEPTED)->first();

        $loan    = collect($this->loans()->whereStatus(Loan::ACCEPTED)->first())->merge([
            'loan_loan_id'          => $loan->identifier.$loan->correlative,
            'loan_wallet_id'        => $loan->wallet->name,
            'loan_requested_amount' => money_format('%i', $amounts->amount),
            'loan_total_amount'     => money_format('%i', $sum_total_amount),
            'loan_pending_amount'   => money_format('%i', $detail->where('status', LoanDetail::ACTIVE)->sum('fee')),
            'loan_last_date'        => 1 == $actual_quote->number_fee ? '-' : $last_quote->payday->toDateTimeString(),
            'loan_current_date'     => $actual_quote->payday->toDateTimeString(),
            'loan_next_date'        => null == $next_quote->payday ? '-' : $next_quote->payday->toDateTimeString(),
            'loan_current_payment'  => money_format('%i', $actual_quote->fee),
        ]);

        return $loan;
    }

    /**
     * Check Client with loan active due.
     */
    public function loanActiveDue()
    {
        $amounts              = $this->loans()->with('amount')->whereStatus(Loan::DUE)->first()->amount;
        $detail               = $this->loans()->whereStatus(Loan::DUE)->first()->detail;
        $actual_quote         = $detail->whereIn('status', [LoanDetail::ACTIVE, LoanDetail::DUE])->first();
        $last_quote           = 1 == $actual_quote->number_fee ? null : $detail->where('number_fee', $actual_quote->number_fee - 1)->first();
        $next_quote           = $detail->where('number_fee', $actual_quote->number_fee + 1)->first();
        $sum_total_amount     = $actual_quote->debt ? $actual_quote->debt + $detail->sum('fee') : $detail->sum('fee');
        $loan                 = $this->loans()->whereStatus(Loan::DUE)->first();
        $loan_pending         = $actual_quote->debt ? $actual_quote->debt + $detail->whereIn('status', [LoanDetail::ACTIVE, LoanDetail::DUE])->sum('fee') : $detail->whereIn('status', [LoanDetail::ACTIVE, LoanDetail::DUE])->sum('fee');
        $loan_current_payment = $actual_quote->debt ? $actual_quote->debt + $actual_quote->fee : $actual_quote->fee;

        $loan    = collect($this->loans()->first())->merge([
            'loan_loan_id'          => $loan->identifier.$loan->correlative,
            'loan_wallet_id'        => $loan->wallet->name,
            'loan_requested_amount' => money_format('%i', $amounts->amount),
            'loan_total_amount'     => money_format('%i', $sum_total_amount),
            'loan_pending_amount'   => money_format('%i', $loan_pending),
            'loan_last_date'        => 1 == $actual_quote->number_fee ? '-' : $last_quote->payday->toDateTimeString(),
            'loan_current_date'     => $actual_quote->payday->toDateTimeString(),
            'loan_next_date'        => null == $next_quote->payday ? '-' : $next_quote->payday->toDateTimeString(),
            'loan_current_payment'  => money_format('%i', $loan_current_payment),
        ]);

        return $loan;
    }

    /**
     * Check Client with loan approved.
     */
    public function loanComplete()
    {
        $loan = collect($this->loans()->first())->merge([
            'days_to_next_loan' => 5,
        ]);

        return $loan;
    }
}
