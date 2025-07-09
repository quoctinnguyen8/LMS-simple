<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CourseRegistration
 * 
 * @property int $id
 * @property int $user_id
 * @property int $course_id
 * @property Carbon $registration_date
 * @property string $status
 * @property string $payment_status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Course $course
 * @property User $user
 *
 * @package App\Models
 */
class CourseRegistration extends Model
{
	protected $table = 'course_registrations';

	protected $casts = [
		'user_id' => 'int',
		'course_id' => 'int',
		'registration_date' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'course_id',
		'registration_date',
		'status',
		'payment_status'
	];

	public function course()
	{
		return $this->belongsTo(Course::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
