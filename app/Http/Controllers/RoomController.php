<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    function index()
    {
        $rooms = Room::all();
        return view('rooms.index', ['rooms' => $rooms]);
    }
    function show($id)
    {
        $room = Room::findOrFail($id);
        return view('rooms.detail', ['room' => $room]);
    }
}
