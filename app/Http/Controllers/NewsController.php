<?php

namespace App\Http\Controllers;

use App\Models\NewsType;
use App\Models\UserNews;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $userID = auth()->id();
        $selectedTypeIds = UserNews::where('user_id', $userID)->pluck('news_type_id')->unique();

        // Áreas a mostrar: las preferidas del usuario o, si no ha personalizado,
        // todas las que tengan noticias (fallback para que el feed nunca quede vacío).
        $typesQuery = NewsType::query()->has('news');
        if ($selectedTypeIds->isNotEmpty()) {
            $typesQuery->whereIn('id', $selectedTypeIds);
        }
        $categories = $typesQuery->orderBy('name')->get();

        $newsData = [];
        foreach ($categories as $category) {
            $newsData[] = (object)[
                'id' => $category->id,
                'category' => $category->name,
                'news' => $category->news()->orderByDesc('created_at')->limit(15)->get(),
            ];
        }

        $news = NewsType::orderBy('name')->pluck('name', 'id');
        $userNewsIds = $selectedTypeIds->values()->toArray();

        return view('news.index', compact('newsData', 'news', 'userNewsIds'));
    }

    public function updatePreferences(Request $request)
    {
        $userID = auth()->id();
        $selectedCategories = $request->input('news', []);

        UserNews::where('user_id', $userID)->delete();

        foreach ($selectedCategories as $categoryId) {
            UserNews::create([
                'user_id' => $userID,
                'news_type_id' => $categoryId,
            ]);
        }

        return redirect()->route('news.index')->with('status', 'Preferences updated successfully!');
    }
}
