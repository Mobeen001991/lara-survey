<?php

namespace App\Services;

use App\Enums\UserListScope;
use App\Filters\UserFilter;
use App\Models\SurveyResponse;
use App\Models\User;
use App\Sorts\UserSort;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class AdminUserService
{
    private const MAX_PER_PAGE = 100;

    private const DEFAULT_PER_PAGE = 10;

    /**
     * @return LengthAwarePaginator<int, User>
     */
    public function paginatedUsers(Request $request): LengthAwarePaginator
    {
        $query = User::query()->with('surveyResponse');

        $query = (new UserFilter($request, UserListScope::AllUsers))->apply($query);
        $query = (new UserSort($request))->apply($query);

        return $query
            ->paginate($this->perPage($request))
            ->appends($request->query());
    }

    /**
     * @return LengthAwarePaginator<int, User>
     */
    public function paginatedIncompleteSurveyUsers(Request $request): LengthAwarePaginator
    {
        $query = User::query()->with('surveyResponse');

        $query = (new UserFilter($request, UserListScope::IncompleteSurvey))->apply($query);
        $query = (new UserSort($request))->apply($query);

        return $query
            ->paginate($this->perPage($request))
            ->appends($request->query());
    }

    private function perPage(Request $request): int
    {
        return min(max($request->integer('per_page', self::DEFAULT_PER_PAGE), 1), self::MAX_PER_PAGE);
    }

    public function findSurveyResponseForUser(int $userId): ?SurveyResponse
    {
        return SurveyResponse::query()
            ->with('user')
            ->where('user_id', $userId)
            ->first();
    }

    /**
     * @param  array<string, int>  $answers
     */
    public function upsertSurveyForUser(int $userId, array $answers): SurveyResponse
    {
        User::query()->findOrFail($userId);

        return SurveyResponse::query()->updateOrCreate(
            ['user_id' => $userId],
            collect($answers)
                ->only(collect(range(1, 10))->map(fn (int $n) => "q{$n}")->all())
                ->all()
        );
    }
}
