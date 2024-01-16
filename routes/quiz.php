<?php

use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'quiz'], function() {

    Route::post('/store', [QuizController::class, 'storeQuiz']);
    Route::get('/all', [QuizController::class, 'getAllQuizzes']);
    Route::get('/delete/{id}', [QuizController::class, 'deleteQuiz']);
    Route::post('/delete/quizzes', [QuizController::class, 'deleteQuizzes']);
});
