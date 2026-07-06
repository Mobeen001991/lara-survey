<?php

namespace App\Http\Requests;

use App\Models\SurveyResponse;
use Illuminate\Foundation\Http\FormRequest;

class StoreSurveyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return SurveyResponse::questionRules();
    }
}
