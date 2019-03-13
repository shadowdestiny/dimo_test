<?php

namespace App;

use App\Traits\HasUuid;

class Step extends BaseModel
{
    use HasUuid;

    public function getRouteKeyName()
    {
        return 'order';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'order'];

    public function questions()
    {
        return $this->hasMany(Question::class, 'step_uuid')->orderBy('order');
    }
}
