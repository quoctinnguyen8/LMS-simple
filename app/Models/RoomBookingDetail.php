<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RoomBookingDetail
 * 
 * @property int $id
 * @property int $room_booking_id
 * @property Carbon $booking_date
 * @property Carbon $start_time
 * @property Carbon $end_time
 * @property string $status
 * @property int|null $approved_by
 * @property int|null $rejected_by
 * @property int|null $cancelled_by
 * @property bool $cancelled_by_customer
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User|null $user
 * @property RoomBooking $room_booking
 *
 * @package App\Models
 */
class RoomBookingDetail extends Model
{
	protected $table = 'room_booking_details';

	protected $casts = [
		'room_booking_id' => 'int',
		'booking_date' => 'datetime',
		'start_time' => 'datetime',
		'end_time' => 'datetime',
		'approved_by' => 'int',
		'rejected_by' => 'int',
		'cancelled_by' => 'int',
		'cancelled_by_customer' => 'bool',
		'is_duplicate' => 'bool'
	];

	protected $fillable = [
		'room_booking_id',
		'booking_date',
		'start_time',
		'end_time',
		'status',
		'approved_by',
		'rejected_by',
		'cancelled_by',
		'cancelled_by_customer',
		'is_duplicate'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'rejected_by');
	}

	public function room_booking()
	{
		return $this->belongsTo(RoomBooking::class);
	}
}
