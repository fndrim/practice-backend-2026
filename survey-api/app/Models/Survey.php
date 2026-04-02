<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Survey extends Model
{

    protected $fillable = ['title', 'description'];

    // у одного опроса много вопросов
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}