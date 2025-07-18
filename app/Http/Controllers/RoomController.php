<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomRequest;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\RoomBooking;
use App\Models\RoomBookingDetail;
use Illuminate\Support\Facades\Auth;
use App\Services\RoomBookingService;

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
        //lịch sử đặt phòng
        $bookingDetails = RoomBookingDetail::whereHas('room_booking', function ($query) use ($id) {
            $query->where('room_id', $id)
                ->where('booking_date', '>=', now()->startOfDay())
                ->where('status', 'approved');
        })
        ->orderBy('booking_date', 'asc')
        ->get();
        return view('rooms.detail', [
            'room' => $room,
            'bookingDetails' => $bookingDetails,
        ]);
    }
    function roomBookings(RoomRequest $request)
    {
        $validated = $request->validated();
        $data = [
            'room_id' => $validated['room_id'],
            'reason' => $validated['reason'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'participants_count' => $validated['participants_count'],
            'repeat_days' => $validated['repeat_days'] ?? [],
            'notes' => $validated['notes'] ?? null,
            'customer_name' => $validated['name'],
            'customer_email' => $validated['email'],
            'customer_phone' => $validated['phone'],
            'status' => 'pending',
        ];
        $roomBookingService = new RoomBookingService();
        $data = $roomBookingService->prepareBookingData($data);
        $booking = RoomBooking::create($data);

        $roomBookingService->createBookingDetails($booking, $data, true, false);
        return redirect()->route('rooms.show', ['id' => $validated['room_id']])
            ->with('success', 'Đặt phòng thành công!');
    }
}
