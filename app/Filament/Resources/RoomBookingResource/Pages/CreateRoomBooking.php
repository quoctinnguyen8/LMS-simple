<?php

namespace App\Filament\Resources\RoomBookingResource\Pages;

use App\Filament\Resources\RoomBookingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Services\RoomBookingService;

class CreateRoomBooking extends CreateRecord
{
    protected static string $resource = RoomBookingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $roomBookingService = new RoomBookingService();
        return $roomBookingService->prepareBookingData($data);
    }

    // sau khi tạo thành công thì thêm data cho bảng details
    protected function afterCreate(): void
    {
        $record = $this->record; // Record vừa được tạo
        $data = $this->data; // Data từ form
        $roomBookingService = new RoomBookingService();
        $roomBookingService->createBookingDetails($record, $data);
    }

    protected function getCreatedNotification(): ?\Filament\Notifications\Notification
    {
        return null; // Tắt thông báo mặc định
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
