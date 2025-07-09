<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RoomBookingGroup
 * 
 * @property int $id
 * @property int $user_id
 * @property int $room_id
 * @property int|null $course_id
 * @property string $title
 * @property string|null $purpose
 * @property Carbon $start_time
 * @property Carbon $end_time
 * @property string $recurrence_type
 * @property string|null $recurrence_days
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Course|null $course
 * @property Room $room
 * @property User $user
 * @property Collection|RoomBooking[] $room_bookings
 *
 * @package App\Models
 */
class RoomBookingGroup extends Model
{
	protected $table = 'room_booking_groups';

	protected $casts = [
		'user_id' => 'int',
		'room_id' => 'int',
		'course_id' => 'int',
		'start_time' => 'datetime',
		'end_time' => 'datetime',
		'start_date' => 'datetime',
		'end_date' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'room_id',
		'course_id',
		'title',
		'purpose',
		'start_time',
		'end_time',
		'recurrence_type',
		'recurrence_days',
		'start_date',
		'end_date',
		'status'
	];

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

	public function room_bookings()
	{
		return $this->hasMany(RoomBooking::class, 'booking_group_id');
	}
}
