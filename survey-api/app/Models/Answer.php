<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id', 
        'question_id', 
        'user_id', 
        'answer_value'
    ];

    // Связь: ответ привязан к пользователю (респонденту)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Связь: ответ привязан к конкретному вопросу
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}