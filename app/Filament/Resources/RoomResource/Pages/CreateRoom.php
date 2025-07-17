<?php

namespace App\Filament\Resources\RoomResource\Pages;

use App\Filament\Resources\RoomResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRoom extends CreateRecord
{
    protected static string $resource = RoomResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $imgFullUrl = asset('storage/' . $data['image']);
        $data['seo_image'] = empty($data['seo_image']) ? $imgFullUrl : trim($data['seo_image']);
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
