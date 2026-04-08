<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SurveyController;
use App\Http\Controllers\Api\AuthController;

// 1. ПУБЛИЧНЫЕ МАРШРУТЫ (Доступны без токена)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// 2. ЗАЩИЩЕННЫЕ МАРШРУТЫ (Нужен Bearer Token)
Route::middleware('auth:sanctum')->group(function () {

    // МАРШРУТЫ ДЛЯ ВСЕХ (И админов, и юзеров)
    Route::get('/surveys', [SurveyController::class, 'index']); // Список опубликованных
    Route::get('/surveys/{survey}', [SurveyController::class, 'show']); // Детали опроса
    Route::post('/answers', [SurveyController::class, 'storeAnswer']); // Пройти опрос (один раз)
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // МАРШРУТЫ ТОЛЬКО ДЛЯ АДМИНИСТРАТОРА (Middleware 'admin')
    Route::middleware('admin')->group(function () {
        Route::post('/surveys', [SurveyController::class, 'store']); // Создать черновик
        
        // Маршруты жизненного цикла (те самые, что выдавали 404)
        Route::patch('/surveys/{survey}/publish', [SurveyController::class, 'publish']); // Опубликовать
        Route::patch('/surveys/{survey}/close', [SurveyController::class, 'close']);     // Закрыть
        
        // Аналитика и результаты
        Route::get('/surveys/{survey}/results', [SurveyController::class, 'results']);
        
        // Удаление
        Route::delete('/surveys/{survey}', [SurveyController::class, 'destroy']);
    });
});