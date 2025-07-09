<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RoomBooking
 * 
 * @property int $id
 * @property int|null $booking_group_id
 * @property int $user_id
 * @property int $room_id
 * @property int|null $course_id
 * @property Carbon $booking_date
 * @property Carbon $start_time
 * @property Carbon $end_time
 * @property string|null $purpose
 * @property bool $is_recurring
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property RoomBookingGroup|null $room_booking_group
 * @property Course|null $course
 * @property Room $room
 * @property User $user
 *
 * @package App\Models
 */
class RoomBooking extends Model
{
	protected $table = 'room_bookings';

	protected $casts = [
		'booking_group_id' => 'int',
		'user_id' => 'int',
		'room_id' => 'int',
		'course_id' => 'int',
		'booking_date' => 'datetime',
		'start_time' => 'datetime',
		'end_time' => 'datetime',
		'is_recurring' => 'bool'
	];

	protected $fillable = [
		'booking_group_id',
		'user_id',
		'room_id',
		'course_id',
		'booking_date',
		'start_time',
		'end_time',
		'purpose',
		'is_recurring',
		'status'
	];

	public function room_booking_group()
	{
		return $this->belongsTo(RoomBookingGroup::class, 'booking_group_id');
	}

	public function course()
	{
		return $this->belongsTo(Course::class);
	}

	public function room()
	{
		return $this->belongsTo(Room::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
