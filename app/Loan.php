<?php

namespace App;

use App\Traits\HasUuid;

class Loan extends BaseModel
{
    use HasUuid;

    const PENDING  = 1; // pendiente
    const APPROVED = 2; // aprobado
    const ACCEPTED = 3; // aceptados
    const REJECTED = 4; // denegados
    const PAID     = 5; // pagados
    const DUE      = 6; // mora

    const STATUS_STRING = [
        self::PENDING   => 'Pendiente',
        self::APPROVED  => 'Aprobado',
        self::ACCEPTED  => 'Aceptado',
        self::REJECTED  => 'Rechazado',
        self::PAID      => 'Pagados',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_uuid',
        'status',
        'level_amount_uuid',
        'comment',
        'wallet_uuid',
        'correlative',
        'identifier',
    ];

    /**
     * Get client belongs to loan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_uuid', 'uuid');
    }

    /**
     * Get amount belongs to loan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function amount()
    {
        return $this->belongsTo(LevelAmount::class, 'level_amount_uuid', 'uuid');
    }

    /**
     * Get wallet belongs to loan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wallet()
    {
        return $this->belongsTo(WalletBrand::class, 'wallet_uuid', 'uuid');
    }

    /**
     * Get client belongs to loan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detail()
    {
        return $this->hasMany(LoanDetail::class, 'loan_uuid', 'uuid')->orderBy('number_fee');
    }

    /**
     * Get client belongs to global amortization.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function global()
    {
        return $this->hasMany(GlobalAmortization::class, 'loan_uuid', 'uuid')->orderBy('day');
    }

    /**
     * Check if loan is approved.
     *
     * @return bool
     */
    public function isApproved()
    {
        return self::APPROVED === $this->status ? true : false;
    }

    /**
     * Check if loan is accepted.
     *
     * @return bool
     */
    public function isAccepted()
    {
        return self::ACCEPTED === $this->status ? true : false;
    }
}
