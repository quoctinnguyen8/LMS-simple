<?php

namespace App\Filament\Resources\NewsResource\Widgets;

use App\Models\News;
use App\Models\NewsCategory;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class NewsStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Tổng số tin tức
        $totalNews = News::count();
        
        // Tin tức đã xuất bản
        $publishedNews = News::where('is_published', true)->count();
        
        // Tin tức nổi bật
        $featuredNews = News::where('is_featured', true)->count();
        
        // Tổng lượt xem
        $totalViews = News::sum('view_count');
        
        // Tin tức mới trong tuần
        $newsThisWeek = News::where('created_at', '>=', now()->subWeek())->count();
        
        // Danh mục có nhiều tin tức nhất
        $topCategory = NewsCategory::withCount('news')
            ->orderBy('news_count', 'desc')
            ->first();

        return [
            Stat::make('Tổng tin tức', $totalNews)
                ->description('Tất cả tin tức trong hệ thống')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('primary'),
                
            Stat::make('Đã xuất bản', $publishedNews)
                ->description($publishedNews . '/' . $totalNews . ' tin tức đã công khai')
                ->descriptionIcon('heroicon-m-eye')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]), // Mock chart data
                
            Stat::make('Tin nổi bật', $featuredNews)
                ->description('Tin tức được đánh dấu nổi bật')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
                
            Stat::make('Tổng lượt xem', number_format($totalViews))
                ->description('Lượt xem tất cả tin tức')
                ->descriptionIcon('heroicon-m-eye')
                ->color('info'),
                
            Stat::make('Tin mới tuần này', $newsThisWeek)
                ->description('Tin tức được tạo trong 7 ngày qua')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('success'),
                
            Stat::make('Danh mục hàng đầu', $topCategory?->name ?? 'Chưa có')
                ->description(($topCategory?->news_count ?? 0) . ' tin tức')
                ->descriptionIcon('heroicon-m-tag')
                ->color('primary'),
        ];
    }
}
