<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SurveyController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});
Route::get('/user', function (Request $request) {
    return UserResource::make(
        $request->user()->loadMissing('surveyResponse')
    );
})->middleware(['auth:sanctum', 'throttle:api']);

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::post('/survey', [SurveyController::class, 'store']);
    Route::get('/survey/status', [SurveyController::class, 'status']);
    Route::get('/survey/statistics', [SurveyController::class, 'statistics']);

    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/users', [AdminController::class, 'users']);
        Route::get('/incomplete-users', [AdminController::class, 'getIncompleteSurveyUsers']);
        Route::get('/survey/incomplete/{userId}', [AdminController::class, 'getSurveyResultsByUser']);
        Route::post('/survey/submit/{userId}', [AdminController::class, 'submitSurveyResultsByUser']);
    });
});
