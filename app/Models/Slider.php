<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_url',
        'link_url',
        'position',
        'is_active',
        'start_date',
        'end_date',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scope để lấy slider đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    // Scope để sắp xếp theo position
    public function scopeOrdered($query)
    {
        return $query->orderBy('position', 'asc');
    }

    // Kiểm tra slider có đang hoạt động không
    public function getIsCurrentlyActiveAttribute(): bool
    {
        return $this->is_active 
            && $this->start_date <= now() 
            && $this->end_date >= now();
    }

    // Lấy trạng thái hiển thị
    public function getStatusAttribute(): string
    {
        // nếu start_date không được đặt, sử dụng thời gian của năm 1900
        $startDate = $this->start_date ?? Carbon::createFromDate(1900, 1, 1);
        // nếu end_date không được đặt, sử dụng thời gian của năm 3000
        $endDate = $this->end_date ?? Carbon::createFromDate(3000, 1, 1);

        if (!$this->is_active) {
            return 'Tắt';
        }
        
        if (now() < $startDate) {
            return 'Chờ hiển thị';
        }
        
        if (now() > $endDate) {
            return 'Hết hạn';
        }
        
        return 'Đang hiển thị';
    }
}
