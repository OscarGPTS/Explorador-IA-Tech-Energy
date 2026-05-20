<?php

namespace App\Http\Controllers;

use App\Models\Recommendation;
use App\Models\RecommendationType;
use App\Models\UserRecommendation;
use Illuminate\Http\Request;

class RecommendationsController extends Controller
{
    public function index()
    {
        return view('recommendations.index');
    }


    public function updatePreferences(Request $request)
    {
        $userID = auth()->id();
        $selectedCategories = $request->input('recommendations', []);

        UserRecommendation::where('user_id', $userID)->delete();

        foreach ($selectedCategories as $categoryId) {
            UserRecommendation::create([
                'user_id' => $userID,
                'recommendation_type_id' => $categoryId,
            ]);
        }

        return redirect()->route('recommendations.index')->with('status', 'Preferences updated successfully!');
    }
}
