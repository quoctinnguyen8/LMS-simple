<?php

namespace App\Mail;

use App\Helpers\SettingHelper;
use App\Models\RoomBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    /**
     * Create a new message instance.
     */
    public function __construct(RoomBooking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $centerName = SettingHelper::get('center_name', 'Hệ thống quản lý học tập');
        
        return new Envelope(
            subject: 'Thông báo xác nhận đặt phòng - ' . $centerName,
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
            view: 'emails.booking-confirmation',
            with: [
                'customerName' => $this->booking->customer_name,
                'bookingCode' => $this->booking->booking_code,
                'roomName' => $this->booking->room->name,
                'roomLocation' => $this->booking->room->location,
                'startDate' => $this->booking->start_date->format('d/m/Y'),
                'endDate' => $this->booking->end_date ? $this->booking->end_date->format('d/m/Y') : null,
                'reason' => $this->booking->reason,
                'status' => $this->booking->status,
                'bookingDetails' => $this->booking->room_booking_details,
                'centerName' => SettingHelper::get('center_name', 'Hệ thống quản lý học tập'),
                'centerAddress' => SettingHelper::get('center_address', ''),
                'centerPhone' => SettingHelper::get('center_phone', ''),
                'centerEmail' => SettingHelper::get('center_email', ''),
                'createdAt' => $this->booking->created_at->format('d/m/Y H:i'),
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
