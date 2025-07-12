<?php

namespace App\Filament\Resources\RoomBookingGroupResource\Pages;

use App\Filament\Resources\RoomBookingGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRoomBookingGroup extends EditRecord
{
    protected static string $resource = RoomBookingGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Đã loại bỏ nút xóa theo yêu cầu
        ];
    }
}
