<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSurveyRequest;
use App\Http\Resources\SurveyResponseResource;
use App\Http\Resources\UserResource;
use App\Services\AdminUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminController extends Controller
{
    public function __construct(
        private readonly AdminUserService $adminUserService
    ) {}

    public function users(Request $request): AnonymousResourceCollection
    {
        return UserResource::collection(
            $this->adminUserService->paginatedUsers($request)
        );
    }

    public function getIncompleteSurveyUsers(Request $request): AnonymousResourceCollection
    {
        return UserResource::collection(
            $this->adminUserService->paginatedIncompleteSurveyUsers($request)
        );
    }

    public function getSurveyResultsByUser(int $userId): JsonResponse|SurveyResponseResource
    {
        $survey = $this->adminUserService->findSurveyResponseForUser($userId);

        if ($survey === null) {
            return response()->json([
                'message' => 'No survey found for this user.',
                'no_user_servey' => true,
            ]);
        }

        return SurveyResponseResource::make($survey);
    }

    public function submitSurveyResultsByUser(StoreSurveyRequest $request, int $userId): JsonResponse
    {
        $this->adminUserService->upsertSurveyForUser($userId, $request->validated());

        return response()->json(['message' => 'Survey submitted successfully']);
    }
}
