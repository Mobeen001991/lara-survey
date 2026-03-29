<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSurveyRequest;
use App\Http\Resources\SurveyStatisticsResource;
use App\Services\SurveyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function __construct(
        private readonly SurveyService $surveyService
    ) {}

    public function store(StoreSurveyRequest $request): JsonResponse
    {
        $this->surveyService->storeForUser(
            $request->user(),
            $request->validated()
        );

        return response()->json(['message' => 'Survey submitted successfully']);
    }

    public function statistics(): SurveyStatisticsResource
    {
        return SurveyStatisticsResource::make(
            $this->surveyService->questionAverages()->all()
        );
    }

    public function status(Request $request): JsonResponse
    {
        $taken = $this->surveyService->userHasCompletedSurvey($request->user());

        return response()->json([
            'taken' => $taken,
        ]);
    }
}
