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
                NavigationGroup::make('Khóa học')
                ->icon('heroicon-o-academic-cap'),
                NavigationGroup::make('Thuê phòng học')
                ->icon('heroicon-o-building-office'),
                NavigationGroup::make('Người dùng')
                    ->icon('heroicon-o-users'),
            ]);
        });
    }
}