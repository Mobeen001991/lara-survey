<?php

namespace App\Sorts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserSort
{
    private const DEFAULT_SORT = '-created_at';

    /**
     * @var list<string>
     */
    private const ALLOWED_FIELDS = ['name', 'email', 'created_at', 'id'];

    public function __construct(
        private readonly Request $request
    ) {}

    public function apply(Builder $query): Builder
    {
        $sort = (string) $this->request->input('sort', self::DEFAULT_SORT);

        [$field, $direction] = $this->parseSort($sort);

        if (! in_array($field, self::ALLOWED_FIELDS, true)) {
            return $query->orderBy('created_at', 'desc');
        }

        return $query->orderBy($field, $direction);
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function parseSort(string $sort): array
    {
        $direction = 'asc';
        $field = $sort;

        if (str_starts_with($field, '-')) {
            $direction = 'desc';
            $field = substr($field, 1);
        }

        return [$field, $direction];
    }
}
