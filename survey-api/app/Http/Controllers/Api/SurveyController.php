<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    // 1. Получить список всех опросов
    public function index()
    {
        $surveys = Survey::all();
        return response()->json([
            'status' => 'success',
            'data' => $surveys
        ]);
    }

    // 2. Получить конкретный опрос со всеми его вопросами
    public function show(Survey $survey)
    {
        // Загружаем связанные вопросы
        $survey->load('questions');
        
        return response()->json([
            'status' => 'success',
            'data' => $survey
        ]);
    }

    // 3. НОВЫЙ МЕТОД: Аналитика результатов
    public function results(Survey $survey)
    {
        // Загружаем опрос вместе с вопросами и ВСЕМИ ответами на эти вопросы
        $survey->load('questions.answers');

        return response()->json([
            'status' => 'success',
            'data' => [
                'survey_title' => $survey->title,
                'total_responses' => $survey->questions->sum(fn($q) => $q->answers->count()),
                'results' => $survey->questions->map(function ($question) {
                    return [
                        'question' => $question->question_text,
                        'type' => $question->type,
                        'answers_count' => $question->answers->count(),
                        'all_answers' => $question->answers->pluck('answer_text')
                    ];
                })
            ]
        ]);
    }
}