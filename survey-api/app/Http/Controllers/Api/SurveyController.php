<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
    /**
     * Посмотреть список всех опубликованных опросов (для всех)
     */
    public function index()
    {
        // Респонденты видят только опубликованные опросы
        $surveys = Survey::where('status', 'published')->with('questions.options')->get();
        return response()->json($surveys);
    }

    /**
     * Создание нового опроса (только для Админа)
     */
    public function store(Request $request)
    {
        // Валидация входных данных
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'questions' => 'required|array',
            'questions.*.text' => 'required|string',
            'questions.*.type' => 'required|in:text,single,multiple',
            'questions.*.options' => 'array' 
        ]);

        // Создаем опрос со статусом черновика
        $survey = Survey::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'user_id' => Auth::id(),
            'status' => 'draft' 
        ]);

        // Создаем вопросы и варианты ответов к ним
        foreach ($data['questions'] as $qData) {
            $question = $survey->questions()->create([
                'question_text' => $qData['text'],
                'type' => $qData['type']
            ]);

            // Если есть варианты ответа (для single/multiple)
            if (!empty($qData['options'])) {
                foreach ($qData['options'] as $optText) {
                    $question->options()->create(['option_text' => $optText]);
                }
            }
        }

        return response()->json($survey->load('questions.options'), 201);
    }

    /**
     * Публикация опроса (только для Админа)
     */
    public function publish(Survey $survey)
    {
        // Проверяем, что это автор или админ
        if ($survey->user_id !== Auth::id()) {
            return response()->json(['message' => 'Это не ваш опрос'], 403);
        }

        $survey->update(['status' => 'published']);
        return response()->json(['message' => 'Опрос опубликован и доступен для прохождения']);
    }

    /**
     * Закрытие опроса (только для Админа)
     */
    public function close(Survey $survey)
    {
        $survey->update(['status' => 'closed']);
        return response()->json(['message' => 'Опрос закрыт, ответы больше не принимаются']);
    }

    /**
     * Сохранение ответов респондента
     */
    public function storeAnswer(Request $request)
    {
        $request->validate([
            'survey_id' => 'required|exists:surveys,id',
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
        ]);

        $survey = Survey::findOrFail($request->survey_id);

        // 1. Проверка жизненного цикла: опрос должен быть опубликован
        if ($survey->status !== 'published') {
            return response()->json(['error' => 'Опрос недоступен для прохождения'], 403);
        }

        // 2. Проверка: один респондент — один раз
        $alreadyPassed = Answer::where('survey_id', $survey->id)
            ->where('user_id', Auth::id())
            ->exists();
        
        if ($alreadyPassed) {
            return response()->json(['error' => 'Вы уже проходили этот опрос'], 403);
        }

        // 3. Сохранение ответов с учетом типов
        foreach ($request->answers as $ans) {
            $question = $survey->questions()->find($ans['question_id']);
            
            // Если одиночный выбор — проверяем, чтобы был только один вариант
            if ($question->type === 'single' && is_array($ans['value']) && count($ans['value']) > 1) {
                return response()->json(['error' => "В вопросе {$question->id} можно выбрать только один вариант"], 422);
            }

            Answer::create([
                'survey_id' => $survey->id,
                'question_id' => $ans['question_id'],
                'user_id' => Auth::id(),
                'answer_value' => is_array($ans['value']) ? json_encode($ans['value']) : $ans['value'],
            ]);
        }

        return response()->json(['message' => 'Ваши ответы успешно сохранены!'], 201);
    }

    /**
     * Просмотр результатов (для автора/админа)
     */
    public function results(Survey $survey)
    {
        // Только админ или автор видит аналитику
        $survey->load('questions.answers');
        return response()->json([
            'survey' => $survey->title,
            'results' => $survey->questions->map(function($q) {
                return [
                    'question' => $q->question_text,
                    'answers' => $q->answers->pluck('answer_value')
                ];
            })
        ]);
    }
}