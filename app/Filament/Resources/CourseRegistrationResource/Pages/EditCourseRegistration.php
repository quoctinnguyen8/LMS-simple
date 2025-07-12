<?php

namespace App\Filament\Resources\CourseRegistrationResource\Pages;

use App\Filament\Resources\CourseRegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCourseRegistration extends EditRecord
{
    protected static string $resource = CourseRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Đã loại bỏ nút xóa theo yêu cầu
        ];
    }
}
