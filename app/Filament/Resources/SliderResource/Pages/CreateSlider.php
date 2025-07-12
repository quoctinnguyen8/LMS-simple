<?php

namespace App\Filament\Resources\SliderResource\Pages;

use App\Filament\Resources\SliderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateSlider extends CreateRecord
{
    protected static string $resource = SliderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Tự động gán người tạo là user hiện tại
        $data['created_by'] = Auth::id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
