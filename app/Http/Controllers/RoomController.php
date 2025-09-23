<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomRequest;
use App\Models\Room;
use App\Models\RoomBooking;
use App\Services\RoomBookingService;
use App\Mail\BookingConfirmationNotification;
use App\Mail\NotifyAdmin;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class RoomController extends Controller
{
    function index()
    {
        $rooms = Room::where('status', 'available') 
            ->orderBy('created_at', 'desc')
            ->get();
        return view('rooms.index', ['rooms' => $rooms]);
    }
    function show($id)
    {
        $room = Room::findOrFail($id);
        return view('rooms.detail', ['room' => $room]);
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
        $booking->refresh();
        Mail::to($booking->customer_email)->send(new BookingConfirmationNotification($booking));
        $adminUsers = User::where('role', '!=' , 'user')->get();
        foreach ($adminUsers as $admin) {
            Mail::to($admin->email)->send(new NotifyAdmin('booking', $booking));
        }
        return redirect()->route('rooms.show', ['id' => $validated['room_id']])
            ->with('success', 'Đặt phòng thành công!');
    }
}
