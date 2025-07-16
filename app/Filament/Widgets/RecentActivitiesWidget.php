<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\RoomBooking;
use App\Models\News;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class RecentActivitiesWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '60s';

    protected ?string $heading = 'Hoạt động gần đây';
    
    protected static ?int $sort = 1;
    
    protected function getStats(): array
    {
        // Đăng ký mới nhất
        $latestRegistration = CourseRegistration::with('course')
            ->latest()
            ->first();
            
        // Đặt phòng mới nhất
        $latestBooking = RoomBooking::with('room')
            ->latest()
            ->first();
            
        // Tin tức mới nhất
        $latestNews = News::latest()->first();
        
        // Khóa học mới nhất
        $latestCourse = Course::latest()->first();
        
        // Đăng ký trong 24h qua
        $registrationsLast24h = CourseRegistration::where('created_at', '>=', now()->subDay())->count();
        
        // Đặt phòng trong 24h qua
        $bookingsLast24h = RoomBooking::where('created_at', '>=', now()->subDay())->count();

        return [
            Stat::make('Đăng ký mới nhất', $latestRegistration?->student_name ?? 'Chưa có')
                ->description($latestRegistration ? 
                    'Khóa: ' . str($latestRegistration->course->title)->limit(30) . ' - ' . 
                    $latestRegistration->created_at->diffForHumans() 
                    : 'Chưa có đăng ký nào'
                )
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('success'),
                
            Stat::make('Đặt phòng mới nhất', $latestBooking?->customer_name ?? 'Chưa có')
                ->description($latestBooking ? 
                    'Phòng: ' . $latestBooking->room->name . ' - ' . 
                    $latestBooking->created_at->diffForHumans()
                    : 'Chưa có đặt phòng nào'
                )
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
                
            Stat::make('Tin tức mới nhất', $latestNews?->title ? str($latestNews->title)->limit(25) : 'Chưa có')
                ->description($latestNews ? 
                    'Xuất bản: ' . ($latestNews->is_published ? 'Có' : 'Chưa') . ' - ' . 
                    $latestNews->created_at->diffForHumans()
                    : 'Chưa có tin tức nào'
                )
                ->descriptionIcon('heroicon-m-document-plus')
                ->color('primary'),
                
            Stat::make('Khóa học mới nhất', $latestCourse?->title ? str($latestCourse->title)->limit(25) : 'Chưa có')
                ->description($latestCourse ? 
                    'Trạng thái: ' . match($latestCourse->status) {
                        'published' => 'Đã xuất bản',
                        'draft' => 'Nháp',
                        'archived' => 'Lưu trữ',
                        default => $latestCourse->status
                    } . ' - ' . $latestCourse->created_at->diffForHumans()
                    : 'Chưa có khóa học nào'
                )
                ->descriptionIcon('heroicon-m-plus-circle')
                ->color('warning'),
                
            Stat::make('Hoạt động 24h', $registrationsLast24h + $bookingsLast24h)
                ->description($registrationsLast24h . ' đăng ký, ' . $bookingsLast24h . ' đặt phòng')
                ->descriptionIcon('heroicon-m-clock')
                ->color('success')
                ->chart([1, 2, 1, 3, 1, 2, 1]), // Mock chart data
        ];
    }
}
