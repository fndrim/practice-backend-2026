<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = ['survey_id', 'question_text', 'type'];

    /**
     * Получить все ответы на этот вопрос.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
}