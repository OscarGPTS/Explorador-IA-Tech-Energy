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
        // Tipos (áreas) que tienen al menos una recomendación, ordenados por nombre.
        $types = RecommendationType::orderBy('name')->get();

        // Recomendaciones agrupadas por tipo (área), más recientes primero.
        $recommendations = Recommendation::with('recommendationType')
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('recommendation_type_id');

        $canManage = auth()->user()?->hasPermission('manage-recommendations') ?? false;

        return view('recommendations.index', compact('types', 'recommendations', 'canManage'));
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
