<?php

namespace App;

use App\Traits\HasUuid;

class ClientWallet extends BaseModel
{
    use HasUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_uuid',
        'number_account',
        'active',
        'wallet_brand_uuid',
    ];

    /**
     * Get client belongs to wallet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
