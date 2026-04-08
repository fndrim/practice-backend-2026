<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Option extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id', 
        'option_text'
    ];

    // Связь: вариант принадлежит конкретному вопросу
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}