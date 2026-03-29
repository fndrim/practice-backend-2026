<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SurveyController;
use App\Http\Controllers\Api\ResponseController;

// Получить список всех опросов
Route::get('/surveys', [SurveyController::class, 'index']);

// Получить один конкретный опрос со всеми вопросами
Route::get('/surveys/{survey}', [SurveyController::class, 'show']);

// НОВЫЙ МАРШРУТ: Получить статистику/результаты опроса
Route::get('/surveys/{survey}/results', [SurveyController::class, 'results']);

// Отправить ответы на опрос
Route::post('/surveys/{survey}/answers', [ResponseController::class, 'store']);

Route::post('/answers', [SurveyController::class, 'storeAnswer']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');