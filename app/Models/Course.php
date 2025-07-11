<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Course
 * 
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property string|null $content
 * @property string|null $featured_image
 * @property float $price
 * @property int $category_id
 * @property Carbon|null $end_registration_date
 * @property Carbon|null $start_date
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Category $category
 * @property Collection|CourseRegistration[] $course_registrations
 * @property Collection|RoomBookingGroup[] $room_booking_groups
 * @property Collection|RoomBooking[] $room_bookings
 *
 * @package App\Models
 */
class Course extends Model
{
	protected $table = 'courses';

	protected $casts = [
		'price' => 'int',
		'category_id' => 'int',
		'created_by' => 'int',
		'is_price_visible' => 'boolean',
		'end_registration_date' => 'datetime',
		'start_date' => 'datetime'
	];

	protected $fillable = [
		'created_by',
		'title',
		'slug',
		'description',
		'content',
		'featured_image',
		'price',
		'is_price_visible',
		'category_id',
		'end_registration_date',
		'start_date',
		'status'
	];

	public function creator()
	{
		return $this->belongsTo(User::class, 'created_by');
	}

	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function course_registrations()
	{
		return $this->hasMany(CourseRegistration::class);
	}

	public function room_booking_groups()
	{
		return $this->hasMany(RoomBookingGroup::class);
	}

	public function room_bookings()
	{
		return $this->hasMany(RoomBooking::class);
	}
}
