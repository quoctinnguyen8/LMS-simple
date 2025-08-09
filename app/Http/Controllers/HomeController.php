<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Course;
use App\Models\News;
use App\Models\Slider;

class HomeController extends Controller
{
    public function index()
    {
        $rooms = Room::where('status', 'available')
            ->orderBy('created_at', 'desc')
            ->get();
        $courses = Course::where('status','published')
            ->where('start_date', '>=', now()->subDays(14))
            ->orderBy('created_at', 'desc')
            ->get();
        $news =  News::where('is_published', true)
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        $slides = Slider::where('is_active', 1)->orderBy('position', 'asc')->get();
        return view('home', compact('rooms', 'courses', 'slides', 'news'));
    }

    public function search()
    {
        $query = request('q');
        $courses = Course::where('title', 'like', "%{$query}%")
            ->get();
        $news = News::where('title', 'like', "%{$query}%")
            ->get();

        return view('search_results', compact('courses', 'news'));
    }
    
    public function contacts()
    {
        return view('contacts');
    }
}
