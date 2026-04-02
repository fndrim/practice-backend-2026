<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['survey_id', 'question_id', 'answer_value', 'answer_text'];
}