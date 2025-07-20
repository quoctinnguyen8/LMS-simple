<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\UserMenuItem;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Filament::serving(function () {
            Filament::registerNavigationGroups([
                NavigationGroup::make('Khóa học'),
                NavigationGroup::make('Thuê phòng học'),
                NavigationGroup::make('Quản lý tin tức'),
                NavigationGroup::make('Trang web'),
            ]);

            // Thêm menu đổi mật khẩu vào User Menu
            Filament::registerUserMenuItems([
                MenuItem::make()
                    ->label('Đổi mật khẩu')
                    ->url(route('filament.admin.pages.change-password'))
                    ->icon('heroicon-o-key')
                    ->sort(10),
            ]);
        });
    }
}