<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\AdminController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/survey', [SurveyController::class, 'store']);
    Route::get('/survey/statistics', [SurveyController::class, 'statistics']);

    // Admin-only routes
    Route::middleware('admin')->group(function () {
        Route::get('/admin/incomplete-users', [AdminController::class, 'incompleteUsers']);
    });
});
