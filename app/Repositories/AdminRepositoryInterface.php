<?php

namespace App\Repositories;

interface AdminRepositoryInterface
{
    /**
     * Get all users paginated.
     *
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllUsersPaginated(int $perPage = 10);

    /**
     * Get non-admin users without a survey response record paginated.
     *
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getIncompleteSurveyUsersPaginated(int $perPage = 10);

    /**
     * Get survey results for a given user ID.
     *
     * @param int $userId
     * @return mixed
     */
    public function getSurveyResultsByUser(int $userId);

    /**
     * Update or create survey responses for a given user.
     *
     * @param array $data
     * @param int $userId
     * @return \App\Models\SurveyResponse
     */
    public function submitSurveyResultsByUser(array $data, int $userId);
}
