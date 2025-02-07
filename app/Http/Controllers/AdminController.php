<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class AdminController extends Controller
{
    // GET /api/admin/incomplete-users
    public function incompleteUsers()
    {
        // Users who do not have a survey response record.
        $users = User::whereDoesntHave('surveyResponse')->get();
        return response()->json($users);
    }
}
