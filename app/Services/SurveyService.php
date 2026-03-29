<?php

namespace App\Services;

use App\Models\SurveyResponse;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Collection;

class SurveyService
{
    private const MAX_QUESTIONS = 10;

    /**
     * @param  array<string, int>  $answers
     */
    public function storeForUser(User $user, array $answers): void
    {
        if (SurveyResponse::query()->where('user_id', $user->id)->exists()) {
            throw new HttpResponseException(
                response()->json(['message' => 'Survey already submitted'], 422)
            );
        }

        try {
            SurveyResponse::query()->create(array_merge($answers, ['user_id' => $user->id]));
        } catch (UniqueConstraintViolationException) {
            throw new HttpResponseException(
                response()->json(['message' => 'Survey already submitted'], 422)
            );
        }
    }

    /**
     * Single aggregate query instead of one AVG() per column.
     *
     * @return Collection<string, mixed>
     */
    public function questionAverages(): Collection
    {
        $select = collect(range(1, self::MAX_QUESTIONS))
            ->map(fn (int $n) => 'AVG(q'.$n.') as avg_q'.$n)
            ->implode(', ');

        $row = SurveyResponse::query()->selectRaw($select)->first();

        return collect(range(1, self::MAX_QUESTIONS))
            ->mapWithKeys(function (int $n) use ($row) {
                $key = 'avg_q'.$n;

                return [$key => $row !== null ? $row->{$key} : null];
            });
    }

    public function userHasCompletedSurvey(User $user): bool
    {
        return SurveyResponse::query()->where('user_id', $user->id)->exists();
    }
}
