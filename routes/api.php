<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\AdminController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::middleware('auth:sanctum')->group(function () {
    Route::post('/survey', [SurveyController::class, 'store']);
    Route::get('/survey/status', [SurveyController::class, 'status']);
    Route::get('/survey/statistics', [SurveyController::class, 'statistics']);

    // Admin-only routes
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/users', [AdminController::class, 'users']);
        Route::get('/incomplete-users', [AdminController::class, 'getIncompleteSurveyUsers']);
        Route::get('/survey/incomplete/{userId}', [AdminController::class, 'getSurveyResultsByUser']);
        Route::post('/survey/submit/{userId}', [AdminController::class, 'submitSurveyResultsByUser']);
    });
});
