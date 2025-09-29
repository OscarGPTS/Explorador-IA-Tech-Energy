<?php

namespace App\Http\Controllers;

use App\Models\NewsType;
use App\Models\UserNews;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $userID = auth()->id();
        $userNews = UserNews::where('user_id', $userID)->get();

        $categories = $userNews->map(function($news) {
            return $news->newsType;
        })->unique();

        $newsData = [];
        foreach ($categories as $category) {
            $newsData[] = (object)[
                'id' => $category->id,
                'category' => $category->name,
                'news' => $category->news 
            ];
        }

        $news = NewsType::orderBy('created_at', 'desc')->pluck('name', 'id');

        $userNewsIds = $categories->pluck('id')->toArray();

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
