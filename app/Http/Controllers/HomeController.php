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
        $courses = Course::all();
        $slides = Slider::where('is_active', 1)->orderBy('position', 'asc')->get();
        return view('home', compact('rooms', 'courses', 'slides'));
    }
}
