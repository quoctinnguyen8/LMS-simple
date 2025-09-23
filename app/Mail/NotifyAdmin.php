<?php

namespace App\Mail;

use App\Helpers\SettingHelper;
use App\Models\RoomBooking;
use App\Models\CourseRegistration; // Assuming this model exists for course registrations
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifyAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $type;
    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct(string $type, $data)
    {
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $centerName = SettingHelper::get('center_name', 'Hệ thống quản lý học tập');
        
        $subject = match ($this->type) {
            'booking' => 'Thông báo đặt phòng mới cho Admin - ' . $centerName,
            'registration' => 'Thông báo đăng ký khóa học mới cho Admin - ' . $centerName,
            default => 'Thông báo mới cho Admin - ' . $centerName,
        };
        
        return new Envelope(
            subject: $subject,
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
        $with = [
            'centerName' => SettingHelper::get('center_name', 'Hệ thống quản lý học tập'),
            'centerAddress' => SettingHelper::get('center_address', ''),
            'centerPhone' => SettingHelper::get('center_phone', ''),
            'centerEmail' => SettingHelper::get('center_email', ''),
            'createdAt' => $this->data->created_at->format('d/m/Y H:i'),
        ];

        if ($this->type === 'booking') {
            $with = array_merge($with, [
                'customerName' => $this->data->customer_name,
                'bookingCode' => $this->data->booking_code,
                'roomName' => $this->data->room->name,
                'roomLocation' => $this->data->room->location,
                'startDate' => $this->data->start_date->format('d/m/Y'),
                'endDate' => $this->data->end_date ? $this->data->end_date->format('d/m/Y') : null,
                'reason' => $this->data->reason,
                'status' => $this->data->status,
                'bookingDetails' => $this->data->room_booking_details,
            ]);
        } elseif ($this->type === 'registration') {
            // Adjust fields for course registration; assuming similar structure
            $with = array_merge($with, [
                'customerName' => $this->data->customer_name,
                'registrationCode' => $this->data->registration_code, // Assuming this field exists
                'courseName' => $this->data->course->name, // Assuming course relationship
                'courseDescription' => $this->data->course->description ?? '', // Optional
                'startDate' => $this->data->start_date->format('d/m/Y'), // Assuming dates exist
                'endDate' => $this->data->end_date ? $this->data->end_date->format('d/m/Y') : null,
                'reason' => $this->data->reason ?? '', // Optional
                'status' => $this->data->status,
            ]);
        }

        return new Content(
            view: 'emails.notify-admin',
            with: $with
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
