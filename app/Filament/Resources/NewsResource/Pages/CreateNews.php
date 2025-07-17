<?php

namespace App\Filament\Resources\NewsResource\Pages;

use App\Filament\Resources\NewsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateNews extends CreateRecord
{
    protected static string $resource = NewsResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // tác giả là người dùng hiện tại
        $data['author_id'] = Auth::id();

        // tạo url bao gồm protocol và domain
        $imgFullUrl = asset('storage/' . $data['featured_image']);
        $data['seo_image'] = empty($data['seo_image']) ? $imgFullUrl : trim($data['seo_image']);
        
        return $data;
    }

    // mẫu - afterCreate
    // protected function afterCreate(): void
    // {
    //     parent::afterCreate();

    //     // Redirect to the list page after creating a news item
    //     $this->redirect(NewsResource::getUrl('index'));
    // }
}
