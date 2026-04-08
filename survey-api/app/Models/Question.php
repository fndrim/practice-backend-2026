<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id', 
        'question_text', 
        'type'
    ];

    // Связь: вопрос принадлежит конкретному опросу
    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    // Связь: у вопроса может быть много вариантов выбора (для single/multiple)
    public function options()
    {
        return $this->hasMany(Option::class);
    }

    // Связь: на этот вопрос может быть много ответов
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}