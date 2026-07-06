<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSurveyRequest;
use App\Models\SurveyResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function store(StoreSurveyRequest $request): JsonResponse
    {
        $user = $request->user();

        if ($user->hasCompletedSurvey()) {
            return response()->json(['message' => 'Survey already submitted'], 422);
        }

        $user->surveyResponse()->create($request->validated());

        return response()->json(['message' => 'Survey submitted successfully']);
    }

    public function statistics(): JsonResponse
    {
        return response()->json(SurveyResponse::averageStatistics());
    }

    public function status(Request $request): JsonResponse
    {
        return response()->json([
            'taken' => $request->user()->hasCompletedSurvey(),
        ]);
    }
}
