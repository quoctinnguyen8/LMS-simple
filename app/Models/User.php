<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'suspended_at',
        'suspended_by',
        'suspension_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'suspended_at' => 'datetime',
        ];
    }
	
	public function course_registrations()
	{
		return $this->hasMany(CourseRegistration::class, 'created_by');
	}

	public function room_booking_groups()
	{
		return $this->hasMany(RoomBookingGroup::class);
	}

	public function room_bookings()
	{
		return $this->hasMany(RoomBooking::class, 'created_by');
	}

	// Quan hệ với người đình chỉ
	public function suspendedBy()
	{
		return $this->belongsTo(User::class, 'suspended_by');
	}

	// Scope để lọc user đang hoạt động
	public function scopeActive($query)
	{
		return $query->where('status', 'active');
	}

	// Scope để lọc user bị đình chỉ
	public function scopeSuspended($query)
	{
		return $query->where('status', 'suspended');
	}

	// Check xem user có bị đình chỉ không
	public function isSuspended(): bool
	{
		return $this->status === 'suspended';
	}

	// Check xem user có đang hoạt động không
	public function isActive(): bool
	{
		return $this->status === 'active';
	}
}
