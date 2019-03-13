<?php

namespace App;

use App\Traits\HasUuid;

class Level extends BaseModel
{
    use HasUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'key',
        'description',
        'active',
        'next_level_amount',
        'next_level_steps',
        'amout',
        'commission',
        'max_loans',
        'max_time',
        'has_30',
    ];

    /**
     * Get all amounts from level.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function amounts()
    {
        return $this->hasMany(LevelAmount::class, 'level_uuid', 'uuid')->orderBy('amount');
    }

    /**
     * Get all client from level.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clients()
    {
        return $this->hasMany(Client::class, 'level_uuid', 'uuid');
    }
}
