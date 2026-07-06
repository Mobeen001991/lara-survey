<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SurveyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());

    Route::post('/survey', [SurveyController::class, 'store']);
    Route::get('/survey/status', [SurveyController::class, 'status']);
    Route::get('/survey/statistics', [SurveyController::class, 'statistics']);

    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/users', [AdminController::class, 'users']);
        Route::get('/incomplete-users', [AdminController::class, 'getIncompleteSurveyUsers']);
        Route::get('/survey/incomplete/{user}', [AdminController::class, 'getSurveyResultsByUser']);
        Route::post('/survey/submit/{user}', [AdminController::class, 'submitSurveyResultsByUser']);
    });
});
