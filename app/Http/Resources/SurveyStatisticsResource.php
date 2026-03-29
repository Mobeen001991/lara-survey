<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin array<string, float|null>|object
 */
class SurveyStatisticsResource extends JsonResource
{
    public static $wrap = null;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return collect(range(1, 10))
            ->mapWithKeys(fn (int $n) => ["avg_q{$n}" => data_get($this->resource, "avg_q{$n}")])
            ->all();
    }
}
