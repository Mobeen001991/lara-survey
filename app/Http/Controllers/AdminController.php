<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SurveyResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{

    // GET /api/admin/incomplete-users
    public function users()
    {
        // Users who do not have a survey response record.
        $users = User::paginate(10);
        return response()->json([
            'users' => $users->items(), // Get current page data
            'total_pages' => $users->lastPage(), // Total pages
            'current_page' => $users->currentPage(),
            'total' => $users->total(),
        ]);
    }
    /**
     * Fetch incomplete survey questions and pre-filled answers.
     */
    public function getIncompleteSurveyUsers()
    {
        $authUser = Auth::user(); 
    
        // Fetch non-admin users who do NOT have a survey response record.
        $incompleteUsers = User::where('is_admin', false)
            ->whereDoesntHave('surveyResponse')
            ->paginate(10);
    
        return response()->json([
            'users' => $incompleteUsers->items(),      // Current page data
            'total_pages' => $incompleteUsers->lastPage(), // Total pages
            'current_page' => $incompleteUsers->currentPage(),
            'total' => $incompleteUsers->total(),       // Total number of records
        ]);
    }

    /**
     * Return survey results for a given user ID.
     *
     * Only administrator may access these results.
     *
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSurveyResultsByUser(Request $request,$userId)
    {
        $authUser = Auth::user(); 
        // If the authenticated user is not admin.
        if (!$authUser->is_admin) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }
        
        // Fetch the survey responses for the given user.
        $survey = SurveyResponse::with('user')->where('user_id', $userId)->first();
        
        if (!$survey) {
            return response()->json(['message' => 'No survey found for this user.','no_user_servey'=>true], 200);
        }
        
        return response()->json($survey);
    }
    /**
     * Return survey results for a given user ID.
     *
     * Only administrator may access these results.
     *
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitSurveyResultsByUser(Request $request,$userId)
    {
        $authUser = Auth::user();

        // Allow access only if the authenticated user is an admin.
        if (!$authUser->is_admin) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }
        $validated = $request->validate([
            'q1'  => 'required|integer|min:0|max:5',
            'q2'  => 'required|integer|min:0|max:5',
            'q3'  => 'required|integer|min:0|max:5',
            'q4'  => 'required|integer|min:0|max:5',
            'q5'  => 'required|integer|min:0|max:5',
            'q6'  => 'required|integer|min:0|max:5',
            'q7'  => 'required|integer|min:0|max:5',
            'q8'  => 'required|integer|min:0|max:5',
            'q9'  => 'required|integer|min:0|max:5',
            'q10' => 'required|integer|min:0|max:5'
        ]);

        $user = Auth::user();


        $validated['user_id'] = $userId;
        SurveyResponse::updateOrCreate(
            ['user_id' => $userId], // Condition to check existing record
            [
                'q1'  => $request->q1,
                'q2'  => $request->q2,
                'q3'  => $request->q3,
                'q4'  => $request->q4,
                'q5'  => $request->q5,
                'q6'  => $request->q6,
                'q7'  => $request->q7,
                'q8'  => $request->q8,
                'q9'  => $request->q9,
                'q10' => $request->q10,
            ]
        );
        

        return response()->json(['message' => 'Survey submitted successfully']);
    }
}
