<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyResponse extends Model
{
    use HasFactory;

    public const QUESTION_COUNT = 10;

    protected $fillable = [
        'user_id',
        'q1', 'q2', 'q3', 'q4', 'q5',
        'q6', 'q7', 'q8', 'q9', 'q10',
    ];

    /**
     * @return array<string, list<string>>
     */
    public static function questionRules(): array
    {
        $rules = [];

        for ($i = 1; $i <= self::QUESTION_COUNT; $i++) {
            $rules["q{$i}"] = ['required', 'integer', 'min:0', 'max:5'];
        }

        return $rules;
    }

    public static function averageStatistics(): ?self
    {
        $columns = collect(range(1, self::QUESTION_COUNT))
            ->map(fn (int $n) => "AVG(q{$n}) as avg_q{$n}")
            ->implode(', ');

        return static::query()->selectRaw($columns)->first();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
