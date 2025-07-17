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
        $imgFullUrl = asset('storage/' . $data['featured_image']);
        $data['seo_image'] = empty($data['seo_image']) ? $imgFullUrl : trim($data['seo_image']);
        return $data;
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
