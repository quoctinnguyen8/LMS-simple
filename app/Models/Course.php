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
		'allow_overflow' => 'boolean',
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
		'status',
		'max_students',
		'allow_overflow',
		'seo_description',
		'seo_title',
		'seo_image'
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

	/**
	 * Get current number of registered students
	 */
	public function getCurrentStudentCount(): int
	{
		return $this->course_registrations()->count();
	}

	/**
	 * Check if course has reached maximum capacity
	 */
	public function hasReachedMaxCapacity(): bool
	{
		if ($this->max_students === null) {
			return false; // No limit set
		}
		
		return $this->getCurrentStudentCount() >= $this->max_students;
	}

	/**
	 * Check if course can accept new registrations
	 */
	public function canAcceptNewRegistrations(): bool
	{
		if ($this->max_students === null) {
			return true; // No limit set
		}
		
		$hasReachedMax = $this->hasReachedMaxCapacity();
		
		// If reached max but overflow is allowed, can still accept
		if ($hasReachedMax && $this->allow_overflow) {
			return true;
		}
		
		// If not reached max, can accept
		return !$hasReachedMax;
	}

	/**
	 * Get available slots for registration
	 */
	public function getAvailableSlots(): ?int
	{
		if ($this->max_students === null) {
			return null; // Unlimited
		}
		
		$available = $this->max_students - $this->getCurrentStudentCount();
		return max(0, $available);
	}
}
