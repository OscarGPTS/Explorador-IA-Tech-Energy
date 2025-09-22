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
        $userID = auth()->id();
        $userRecommendations = UserRecommendation::where('user_id', $userID)->get();
        
        $categories = $userRecommendations->map(function($rec) {
            return $rec->recommendationType;
        })->unique();
        
        $recommendationData = [];
        foreach ($categories as $category) {
            $recommendationData[] = (object)[
                'id' => $category->id,
                'category' => $category->name,
                'recommendations' => $category->recommendations
            ];
        }

        $recommendations = RecommendationType::all()->pluck('name', 'id');
        $userRecommendationsIds=  $categories->pluck('id')->toArray();
      
        return view('recommendations.index', compact('recommendationData', 'recommendations', 'userRecommendationsIds'));

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
