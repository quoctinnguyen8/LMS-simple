<?php

namespace App\Filament\Resources\CourseRegistrationResource\Pages;

use App\Filament\Resources\CourseRegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateCourseRegistration extends CreateRecord
{
    protected static string $resource = CourseRegistrationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Tự động gán người tạo là user hiện tại nếu chưa có
        if (!isset($data['created_by']) || !$data['created_by']) {
            $data['created_by'] = Auth::id();
        }

        // Lưu giá thực tế của khóa học tại thời điểm đăng ký
        // if (isset($data['course_id'])) {
        //     $course = \App\Models\Course::find($data['course_id']);
        //     if ($course) {
        //         $data['actual_price'] = $course->price;
        //     }
        // }

        return $data;
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
