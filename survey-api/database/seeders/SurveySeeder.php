<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    // Создаем опрос
    $survey = \App\Models\Survey::create([
        'title' => 'Опрос о качестве сервиса',
        'description' => 'Пожалуйста, ответьте на вопросы о нашем API'
    ]);

    // Добавляем к нему пару вопросов
    $survey->questions()->createMany([
        [
            'question_text' => 'Как вам скорость работы?',
            'type' => 'single'
        ],
        [
            'question_text' => 'Что бы вы хотели улучшить?',
            'type' => 'text'
        ]
    ]);
}
}
