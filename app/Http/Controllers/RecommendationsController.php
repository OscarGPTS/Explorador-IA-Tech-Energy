<?php

namespace App\Http\Controllers;

use App\Models\UserRecommendation;
use Illuminate\Http\Request;

class RecommendationsController extends Controller
{
    public function index()
    {
        $userID = auth()->id();
        $recomendations = UserRecommendation::where('user_id', $userID)->get();

    
        dd($recomendations);

        return view('recommendations.index');

    }
}
