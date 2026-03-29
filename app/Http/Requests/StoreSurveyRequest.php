<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSurveyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return self::surveyQuestionRules();
    }

    /**
     * @return array<string, array<int, string>>
     */
    public static function surveyQuestionRules(): array
    {
        return collect(range(1, 10))
            ->mapWithKeys(fn (int $n) => ["q{$n}" => ['required', 'integer', 'min:0', 'max:5']])
            ->all();
    }
}
