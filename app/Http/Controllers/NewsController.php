<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->paginate(10);
        return view('news.index', compact('news'));
    }

    public function category($slug)
    {
        $newsCategory = NewsCategory::where('slug', $slug)->firstOrFail();
        $news = News::where('category_id', $newsCategory->id)
            ->where('is_published', true)
            ->get();
        return view('news.index', compact('newsCategory', 'news'));
    }

    public function show($slug)
    {
        $news_item = News::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
        $news_item->increment('view_count');
        return view('news.detail', compact('news_item'));
    }
}
