<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Survey extends Model
{
    // Разрешаем массовое заполнение полей
    protected $fillable = ['title', 'description'];

    // Указываем, что у одного опроса много вопросов
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}