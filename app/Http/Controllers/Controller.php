<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

abstract class Controller
{
    protected function paginatedJson(LengthAwarePaginator $paginator, string $dataKey = 'items'): JsonResponse
    {
        return response()->json([
            $dataKey => $paginator->items(),
            'total_pages' => $paginator->lastPage(),
            'current_page' => $paginator->currentPage(),
            'total' => $paginator->total(),
        ]);
    }
}
