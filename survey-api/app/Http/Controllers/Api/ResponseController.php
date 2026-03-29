<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\Answer;
use Illuminate\Http\Request;

class ResponseController extends Controller
{
    public function store(Request $request, Survey $survey)
    {
        // 1. Валидация: проверяем, что пришел массив ответов
        $validated = $request->validate([
            'responses' => 'required|array',
            'responses.*.question_id' => 'required|exists:questions,id',
            'responses.*.answer_value' => 'required|string',
        ]);

        // 2. Сохраняем каждый ответ в таблицу answers
        foreach ($validated['responses'] as $item) {
            Answer::create([
                'question_id' => $item['question_id'],
                'answer_value' => $item['answer_value'],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Ваши ответы успешно сохранены!'
        ], 201);
    }
}