<?php

namespace App\Filament\Widgets;

use App\Models\CourseRegistration;
use App\Models\RoomBooking;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class RegistrationTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Xu hướng đăng ký 7 ngày qua';
    
    protected static ?int $sort = 4;
    
    protected static ?string $pollingInterval = '300s';

    protected static ?string $maxHeight = '400px';
    protected static ?string $minHeight = '250px';
    // colspan
    protected int | string | array $columnSpan = 2;

    protected function getData(): array
    {
        // Lấy dữ liệu 7 ngày qua
        $days = collect(range(6, 0))->map(function ($daysBack) {
            $date = now()->subDays($daysBack);
            return [
                'date' => $date->format('d/m'),
                'course_registrations' => CourseRegistration::whereDate('created_at', $date->toDateString())->count(),
                'room_bookings' => RoomBooking::whereDate('created_at', $date->toDateString())->count(),
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Đăng ký khóa học',
                    'data' => $days->pluck('course_registrations')->toArray(),
                    'backgroundColor' => '#3B82F6',
                    'borderColor' => '#1D4ED8',
                ],
                [
                    'label' => 'Đặt phòng',
                    'data' => $days->pluck('room_bookings')->toArray(),
                    'backgroundColor' => '#10B981',
                    'borderColor' => '#059669',
                ],
            ],
            'labels' => $days->pluck('date')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
    
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}
