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
 * @property string|null $user_name
 * @property string|null $user_email
 * @property string|null $user_phone
 * @property string|null $user_address
 * @property Carbon|null $user_birth_date
 * @property string $user_gender
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
		'created_by' => 'int',
		'course_id' => 'int',
		'registration_date' => 'datetime',
		'student_birth_date' => 'datetime'
	];

	protected $fillable = [
		'created_by',
		'course_id',
		'registration_date',
		'status',
		'payment_status',
		'actual_price',
		'student_name',
		'student_email',
		'student_phone',
		'student_address',
		'student_birth_date',
		'student_gender'
	];

	public function creator()
	{
		return $this->belongsTo(User::class, 'created_by');
	}

	public function course()
	{
		return $this->belongsTo(Course::class);
	}
}
