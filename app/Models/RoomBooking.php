<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RoomBooking
 * 
 * @property int $id
 * @property int|null $room_id
 * @property string|null $reason
 * @property Carbon $start_date
 * @property Carbon|null $end_date
 * @property string $status
 * @property int|null $approved_by
 * @property int|null $rejected_by
 * @property int|null $cancelled_by
 * @property int|null $created_by
 * @property string|null $customer_name
 * @property string|null $customer_email
 * @property string|null $customer_phone
 * @property string|null $booking_code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User|null $user
 * @property Room|null $room
 * @property Collection|RoomBookingDetail[] $room_booking_details
 *
 * @package App\Models
 */
class RoomBooking extends Model
{
	protected $table = 'room_bookings';

	protected $casts = [
		'room_id' => 'int',
		'start_date' => 'date',
		'end_date' => 'date',
		'approved_by' => 'int',
		'rejected_by' => 'int',
		'cancelled_by' => 'int',
		'created_by' => 'int',
		'repeat_days' => 'array'
	];

	protected $fillable = [
		'room_id',
		'reason',
		'start_date',
		'end_date',
		'start_time',
		'end_time',
		'participants_count',
		'notes',
		'status',
		'is_duplicate',
		'approved_by',
		'rejected_by',
		'cancelled_by',
		'created_by',
		'customer_name',
		'customer_email',
		'customer_phone',
		'booking_code',
		'repeat_days'
	];

	public function room()
	{
		return $this->belongsTo(Room::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'created_by');
	}

	public function approvedBy()
	{
		return $this->belongsTo(User::class, 'approved_by');
	}

	public function rejectedBy()
	{
		return $this->belongsTo(User::class, 'rejected_by');
	}

	public function cancelledBy()
	{
		return $this->belongsTo(User::class, 'cancelled_by');
	}

	public function room_booking_details()
	{
		return $this->hasMany(RoomBookingDetail::class);
	}
}
