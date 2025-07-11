<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCourse extends EditRecord
{
    protected static string $resource = CourseResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\DeleteAction::make(),
    //     ];
    // }
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Xử lý logic đặc biệt khi update nếu cần
        // Ví dụ: log thay đổi, validation custom, etc.
        
        return $data;
    }
}
