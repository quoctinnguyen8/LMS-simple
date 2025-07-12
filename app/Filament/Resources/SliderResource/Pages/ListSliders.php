<?php

namespace App\Filament\Resources\SliderResource\Pages;

use App\Filament\Resources\SliderResource;
use App\Models\Slider;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Collection;

class ListSliders extends ListRecords
{
    protected static string $resource = SliderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function handleRecordReorder(array $order): void
    {
        // Cập nhật position của các slider theo thứ tự mới
        foreach ($order as $index => $recordId) {
            Slider::where('id', $recordId)->update(['position' => $index + 1]);
        }

        // Hiển thị thông báo thành công
        $this->notify('success', 'Đã cập nhật thứ tự slider thành công!');
    }

    protected function getTableReorderColumn(): ?string
    {
        return 'position';
    }

    protected function canReorderRecords(): bool
    {
        return true;
    }
}
