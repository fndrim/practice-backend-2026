<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Survey;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SurveyApiTest extends TestCase
{
    // Эта строка будет очищать базу перед каждым тестом, чтобы данные не перемешивались
    use RefreshDatabase;

    /** @test */
    public function it_can_get_survey_details()
    {
        // 1. Подготовка: Создаем тестовый опрос прямо в памяти
        $survey = Survey::create([
            'title' => 'Тестовый опрос для автотеста',
            'description' => 'Описание'
        ]);

        // 2. Действие: Просим программу "саму себя" вызвать эндпоинт
        $response = $this->getJson("/api/surveys/{$survey->id}");

        // 3. Проверка: Ожидаем статус 200 (ОК) и наличие заголовка в ответе
        $response->assertStatus(200)
                 ->assertJsonPath('data.title', 'Тестовый опрос для автотеста');
    }
}