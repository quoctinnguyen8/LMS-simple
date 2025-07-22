<?php

namespace App\Mail;

use App\Helpers\SettingHelper;
use App\Models\CourseRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CourseRegistrationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $courseRegistration;

    /**
     * Create a new message instance.
     */
    public function __construct(CourseRegistration $courseRegistration)
    {
        $this->courseRegistration = $courseRegistration;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $centerName = SettingHelper::get('center_name', 'Hệ thống quản lý học tập');
        
        return new Envelope(
            subject: 'Thông báo đăng ký khóa học - ' . $centerName,
            from: new Address(
                config('mail.from.address', 'noreply@example.com'),
                $centerName
            ),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.course-registration',
            with: [
                'studentName' => $this->courseRegistration->student_name,
                'courseTitle' => $this->courseRegistration->course->title,
                'categoryName' => $this->courseRegistration->course->category->name,
                'registrationDate' => $this->courseRegistration->registration_date->format('d/m/Y'),
                'coursePrice' => number_format($this->courseRegistration->course->price, 0, ',', '.'),
                'actualPrice' => number_format($this->courseRegistration->actual_price, 0, ',', '.'),
                'paymentStatus' => $this->courseRegistration->payment_status,
                'status' => $this->courseRegistration->status,
                'courseStartDate' => $this->courseRegistration->course->start_date?->format('d/m/Y'),
                'centerName' => SettingHelper::get('center_name', 'Hệ thống quản lý học tập'),
                'centerPhone' => SettingHelper::get('center_phone', ''),
                'centerEmail' => SettingHelper::get('center_email', ''),
                'centerAddress' => SettingHelper::get('center_address', ''),
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
