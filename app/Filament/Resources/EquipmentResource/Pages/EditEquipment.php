<?php

namespace App\Filament\Resources\EquipmentResource\Pages;

use App\Filament\Resources\EquipmentResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditEquipment extends EditRecord
{
    protected static string $resource = EquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Nếu is_free = true thì xóa giá (set null)
        if (isset($data['is_free']) && $data['is_free'] === true) {
            $data['price'] = 0;
        }
        // Nếu is_free = false thì giá phải > 1000
        // Nếu is_free = false và có giá thì kiểm tra giá tối thiểu
        elseif (isset($data['price']) && $data['price'] < 1000 && (!isset($data['is_free']) || $data['is_free'] === false)) {
            // Hiển thị notification lỗi
            Notification::make()
                ->title('Lỗi giá thiết bị')
                ->body('Giá thuê thiết bị phải lớn hơn 1000₫')
                ->danger()
                ->send();
            // Dừng quá trình save
            $this->halt();
        }

        return $data;
    }
}
