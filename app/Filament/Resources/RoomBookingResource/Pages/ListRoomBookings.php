<?php

namespace App\Filament\Resources\RoomBookingResource\Pages;

use App\Filament\Resources\RoomBookingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\HtmlString;

class ListRoomBookings extends ListRecords
{
    protected static string $resource = RoomBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            // Inject script through footer
        ];
    }

    public function mount(): void
    {
        parent::mount();
        
        // Inject JavaScript for room booking details actions
        FilamentView::registerRenderHook(
            'panels::body.end',
            fn (): string => '<script src="' . asset('js/room-booking-details.js') . '"></script>'
        );
    }
}
