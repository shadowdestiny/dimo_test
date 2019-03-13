<?php

namespace App;

use App\Traits\HasUuid;

class LevelAmount extends BaseModel
{
    use HasUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['amount', 'available','has_30'];

    /**
     * Get level belong to level amount.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function level()
    {
        return $this->belongsTo(Level::class, 'level_uuid');
    }

    /**
     * Get all wallets from client.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function loans()
    {
        return $this->hasMany(Loan::class, 'level_amount_uuid', 'uuid');
    }
}
