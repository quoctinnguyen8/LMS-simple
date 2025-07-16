<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Room
 * 
 * @property int $id
 * @property string $name
 * @property int $capacity
 * @property string|null $location
 * @property string|null $description
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|RoomBookingGroup[] $room_booking_groups
 * @property Collection|RoomBooking[] $room_bookings
 * @property Collection|Equipment[] $equipment
 *
 * @package App\Models
 */
class Room extends Model
{
	protected $table = 'rooms';

	protected $casts = [
		'capacity' => 'int'
	];

	protected $fillable = [
		'name',
		'capacity',
		'location',
		'description',
		'status',
		'price',
		'image',
		'seo_description',
		'seo_title',
		'seo_image'
	];

	public function room_booking_groups()
	{
		return $this->hasMany(RoomBookingGroup::class);
	}

	public function room_bookings()
	{
		return $this->hasMany(RoomBooking::class);
	}

	public function equipment()
	{
		return $this->belongsToMany(Equipment::class, 'room_equipments')
					->withTimestamps();
	}
}
