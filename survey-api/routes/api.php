<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SurveyController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ResponseController;

// Регистрация и вход (выдают токен)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Просмотр списка опросов и конкретного опроса доступен всем
Route::get('/surveys', [SurveyController::class, 'index']);
Route::get('/surveys/{survey}', [SurveyController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    
    // Получить результаты/аналитику может только авторизованный пользователь
    Route::get('/surveys/{survey}/results', [SurveyController::class, 'results']);
    
    // Отправка ответов теперь тоже под защитой (опционально, зависит от задачи)
    Route::post('/answers', [SurveyController::class, 'storeAnswer']);
    
    // Получить данные текущего юзера
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});