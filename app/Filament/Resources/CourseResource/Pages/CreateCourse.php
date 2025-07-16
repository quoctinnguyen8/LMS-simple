<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateCourse extends CreateRecord
{
    protected static string $resource = CourseResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Tự động gán người tạo là user hiện tại nếu chưa có
        if (!isset($data['created_by']) || !$data['created_by']) {
            $data['created_by'] = Auth::id();
        }
        // Tự động gán seo_image từ featured_image nếu có
        if (isset($data['featured_image']) && $data['featured_image']) {
            $data['seo_image'] = $data['featured_image'];
        }
        return $data;
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
