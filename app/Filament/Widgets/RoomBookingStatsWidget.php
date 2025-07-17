<?php

namespace App\Filament\Widgets;

use App\Models\RoomBooking;
use App\Models\Room;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class RoomBookingStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected static ?int $sort = 2; // Hiển thị thứ ba

    protected ?string $heading = 'Thống kê đặt phòng';
    
    protected function getStats(): array
    {
        
        // Đặt phòng chờ duyệt
        $pendingBookings = RoomBooking::where('status', 'pending')->count();
        
        // Đặt phòng tuần này
        $thisWeekBookings = RoomBooking::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();
        
        // Phòng được đặt nhiều nhất
        $popularRoom = Room::withCount(['room_bookings' => function ($query) {
            $query->where('status', 'approved');
        }])
        ->orderBy('room_bookings_count', 'desc')
        ->first();
        
        // Đặt phòng đang diễn ra (hôm nay)
        $ongoingBookings = RoomBooking::where('status', 'approved')
            ->whereDate('start_date', '<=', today())
            ->whereDate('end_date', '>=', today())
            ->count();

        return [
                
            Stat::make('Chờ duyệt', $pendingBookings)
                ->description('Yêu cầu cần xử lý')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
                
            Stat::make('Đang diễn ra', $ongoingBookings)
                ->description('Đặt phòng hôm nay')
                ->descriptionIcon('heroicon-m-home-modern')
                ->color('info'),
                
            Stat::make('Đặt tuần này', $thisWeekBookings)
                ->description('Yêu cầu trong 7 ngày')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),
                
            Stat::make('Phòng phổ biến', $popularRoom?->name ?? 'Chưa có')
                ->description(($popularRoom?->room_bookings_count ?? 0) . ' lần được đặt')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
        ];
    }
}
