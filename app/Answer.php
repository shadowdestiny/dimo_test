<?php

namespace App;

use App\Traits\HasUuid;

class Answer extends BaseModel
{
    use HasUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type', 'response', 'client_uuid'];

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
