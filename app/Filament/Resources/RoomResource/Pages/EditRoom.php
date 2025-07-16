<?php

namespace App\Filament\Resources\RoomResource\Pages;

use App\Filament\Resources\RoomResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRoom extends EditRecord
{
    protected static string $resource = RoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Đã loại bỏ nút xóa theo yêu cầu
        ];
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Tự động gán seo_image từ image nếu có
        if (isset($data['image']) && $data['image']) {
            $data['seo_image'] = $data['image'];
        }
        return $data;
    }
}
