<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
{
    //  Создаем один опрос
    $survey = \App\Models\Survey::create([
        'title' => 'Тестовый опрос',
        'description' => 'Проверка работы API на защите'
    ]);

    // Добавляем к нему один вопрос
    $question = $survey->questions()->create([
        'question_text' => 'Как вы оцениваете работу системы?'
    ]);

    // готовый ответ
    $question->answers()->create([
        'answer_text' => 'Все работает отлично!',
        'answer_value' => '5',
    ]);
}
}
