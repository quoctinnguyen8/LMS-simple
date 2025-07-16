<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
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
        });
    }
}