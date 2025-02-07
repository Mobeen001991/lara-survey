<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SurveyResponse;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
    // POST /api/survey
    public function store(Request $request)
    {
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
            'q10' => 'required|integer|min:0|max:5',
        ]);

        $user = Auth::user();

        if (SurveyResponse::where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'Survey already submitted'], 422);
        }

        $validated['user_id'] = $user->id;
        SurveyResponse::create($validated);

        return response()->json(['message' => 'Survey submitted successfully']);
    }

    // GET /api/survey/statistics
    public function statistics()
    {
        $stats = SurveyResponse::query()
            ->selectRaw(
                'AVG(q1) as avg_q1, AVG(q2) as avg_q2, AVG(q3) as avg_q3, AVG(q4) as avg_q4, AVG(q5) as avg_q5,
                 AVG(q6) as avg_q6, AVG(q7) as avg_q7, AVG(q8) as avg_q8, AVG(q9) as avg_q9, AVG(q10) as avg_q10'
            )
            ->first();

        return response()->json($stats);
    }
}
