<?php

namespace App;

use App\Traits\HasUuid;

class Option extends BaseModel
{
    use HasUuid;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['text'];

    /**
     * Option belogns to question.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_uuid');
    }
}
