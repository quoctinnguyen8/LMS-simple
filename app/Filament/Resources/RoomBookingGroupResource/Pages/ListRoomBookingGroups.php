<?php

namespace App\Filament\Resources\RoomBookingGroupResource\Pages;

use App\Filament\Resources\RoomBookingGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRoomBookingGroups extends ListRecords
{
    protected static string $resource = RoomBookingGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
