<?php

namespace App;

use App\Traits\HasUuid;

class Question extends BaseModel
{
    use HasUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['step_uuid', 'order', 'text', 'type', 'is_profile', 'required'];

    /**
     * Get all options belogns to question.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function options()
    {
        return $this->hasMany(Option::class, 'question_uuid', 'uuid');
    }

    /**
     * Get all answers belogns to question.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_uuid', 'uuid');
    }
}
