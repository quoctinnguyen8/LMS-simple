<?php

namespace App\Filament\Resources\RoomBookingResource\Pages;

use App\Filament\Resources\RoomBookingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRoomBooking extends ViewRecord
{
    protected static string $resource = RoomBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
