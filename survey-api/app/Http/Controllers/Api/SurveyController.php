<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use Illuminate\Http\Request;
use App\Models\Answer;

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

    public function store(Request $request) {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string'
        ]);
    
        // Создаем опрос
        $survey = Survey::create($data);
    
        // Сразу создаем для него тестовый вопрос
        $survey->questions()->create([
            'question_text' => 'Как вам наш сервис?',
            'type' => 'text'
        ]);
    
        return response()->json([
            'status' => 'success',
            'message' => 'Опрос и вопрос созданы!',
            'survey' => $survey
        ], 201);
    }

    public function storeAnswer(Request $request) 
{
    $request->validate([
        'survey_id' => 'required|exists:surveys,id',
        'answers' => 'required|array',
    ]);

    foreach ($request->answers as $answer) {
        Answer::create([
            'survey_id' => $request->survey_id,
            'question_id' => $answer['question_id'],
            'answer_value' => $answer['answer_text'], 
            'answer_text' => $answer['answer_text'], 
        ]);
    }

    return response()->json(['message' => 'Ответы успешно сохранены!'], 201);
}

    // Аналитика результатов
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