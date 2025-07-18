<?php

namespace App\Filament\Resources\CourseRegistrationResource\Pages;

use App\Filament\Resources\CourseRegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EditCourseRegistration extends EditRecord
{
    protected static string $resource = CourseRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Đã loại bỏ nút xóa theo yêu cầu
        ];
    }

    protected function afterSave(): void
    {
        $record = $this->record;
        
        Log::info('Course registration edited via edit page', [
            'registration_id' => $record->id,
            'course_id' => $record->course_id,
            'course_title' => $record->course->title ?? 'Unknown',
            'student_name' => $record->student_name,
            'student_phone' => $record->student_phone,
            'student_email' => $record->student_email,
            'status' => $record->status,
            'payment_status' => $record->payment_status,
            'actual_price' => $record->actual_price,
            'edited_by_user_id' => Auth::id(),
            'edited_by_user_name' => Auth::user()->name ?? 'Unknown',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
