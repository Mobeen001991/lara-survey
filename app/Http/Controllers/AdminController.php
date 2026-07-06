<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSurveyRequest;
use App\Models\SurveyResponse;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    public function users(): JsonResponse
    {
        return $this->paginatedJson(User::paginate(10), 'users');
    }

    public function getIncompleteSurveyUsers(): JsonResponse
    {
        $users = User::query()
            ->nonAdmin()
            ->withoutSurvey()
            ->paginate(10);

        return $this->paginatedJson($users, 'users');
    }

    public function getSurveyResultsByUser(User $user): JsonResponse
    {
        $survey = SurveyResponse::query()
            ->with('user')
            ->where('user_id', $user->id)
            ->first();

        if (! $survey) {
            return response()->json([
                'message' => 'No survey found for this user.',
                'no_user_survey' => true,
            ]);
        }

        return response()->json($survey);
    }

    public function submitSurveyResultsByUser(StoreSurveyRequest $request, User $user): JsonResponse
    {
        SurveyResponse::updateOrCreate(
            ['user_id' => $user->id],
            $request->validated(),
        );

        return response()->json(['message' => 'Survey submitted successfully']);
    }
}
