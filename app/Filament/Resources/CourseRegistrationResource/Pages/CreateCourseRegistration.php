<?php

namespace App\Filament\Resources\CourseRegistrationResource\Pages;

use App\Filament\Resources\CourseRegistrationResource;
use App\Mail\CourseRegistrationNotification;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CreateCourseRegistration extends CreateRecord
{
    protected static string $resource = CourseRegistrationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Tự động gán người tạo là user hiện tại nếu chưa có
        if (!isset($data['created_by']) || !$data['created_by']) {
            $data['created_by'] = Auth::id();
        }

        return $data;
    }
    protected function afterCreate(): void
    {
        $record = $this->record;
        Log::info('Course registration created', [
            'registration_id' => $record->id,
            'course_id' => $record->course_id,
            'course_title' => $record->course->title ?? 'Unknown',
            'student_name' => $record->student_name,
            'student_phone' => $record->student_phone,
            'student_email' => $record->student_email,
            'student_address' => $record->student_address,
            'student_birth_date' => $record->student_birth_date,
            'student_gender' => $record->student_gender,
            'status' => $record->status,
            'payment_status' => $record->payment_status,
            'actual_price' => $record->actual_price,
            'registration_date' => $record->registration_date,
            'created_by_user_id' => Auth::id(),
            'created_by_user_name' => Auth::user()->name ?? 'Unknown',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
