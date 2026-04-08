<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'description', 
        'status', 
        'user_id'
    ];

    // Связь: у одного опроса много вопросов
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    // Связь: опрос принадлежит автору (админу)
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Связь: у опроса много ответов от разных людей
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}