<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Course;

class HomeController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        $courses = Course::all();
        return view('home', compact('rooms', 'courses'));
    }
}
