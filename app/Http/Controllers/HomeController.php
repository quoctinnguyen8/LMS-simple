<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Course;
use App\Models\Slider;

class HomeController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        $courses = Course::where('status','!=', 'draft')
            ->where('end_registration_date', '>=', now())
            ->orderBy('created_at', 'desc')
            ->get();
        $slides = Slider::where('is_active', 1)->orderBy('position', 'asc')->get();
        return view('home', compact('rooms', 'courses', 'slides'));
    }
    public function contacts()
    {
        return view('contacts');
    }
}
