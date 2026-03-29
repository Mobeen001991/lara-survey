<?php

namespace App\Filters;

use App\Enums\UserListScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserFilter
{
    /**
     * @var array<string, callable(Builder, mixed): Builder>
     */
    private array $handlers;

    public function __construct(
        private readonly Request $request,
        private readonly UserListScope $scope = UserListScope::AllUsers
    ) {
        $this->handlers = $this->registerHandlers();
    }

    public function apply(Builder $query): Builder
    {
        $query = $this->applyScopeConstraints($query);

        /** @var array<string, mixed>|mixed $filters */
        $filters = $this->request->input('filter', []);

        if (! is_array($filters)) {
            return $query;
        }

        foreach ($filters as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            $handler = $this->handlers[$key] ?? null;
            if ($handler === null) {
                continue;
            }

            $query = $handler($query, $value);
        }

        return $query;
    }

    /**
     * Override in a subclass to add or replace filters as the app grows.
     *
     * @return array<string, callable(Builder, mixed): Builder>
     */
    protected function registerHandlers(): array
    {
        $handlers = [
            'name' => fn (Builder $query, mixed $value): Builder => $this->applyPartialMatch($query, 'name', $value),
            'email' => fn (Builder $query, mixed $value): Builder => $this->applyPartialMatch($query, 'email', $value),
        ];

        if ($this->scope === UserListScope::AllUsers) {
            $handlers['is_admin'] = fn (Builder $query, mixed $value): Builder => $this->applyExactBoolean(
                $query,
                'is_admin',
                $value
            );
        }

        return $handlers;
    }

    private function applyScopeConstraints(Builder $query): Builder
    {
        return match ($this->scope) {
            UserListScope::AllUsers => $query,
            UserListScope::IncompleteSurvey => $query
                ->where('is_admin', false)
                ->whereDoesntHave('surveyResponse'),
        };
    }

    private function applyPartialMatch(Builder $query, string $column, mixed $value): Builder
    {
        $needle = '%'.$this->escapeLikeString((string) $value).'%';

        return $query->where($column, 'like', $needle);
    }

    private function applyExactBoolean(Builder $query, string $column, mixed $value): Builder
    {
        $bool = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if ($bool === null) {
            $bool = (bool) $value;
        }

        return $query->where($column, $bool);
    }

    private function escapeLikeString(string $value): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $value);
    }
}
