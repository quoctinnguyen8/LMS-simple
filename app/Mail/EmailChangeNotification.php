<?php

namespace App\Mail;

use App\Helpers\SettingHelper;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailChangeNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $oldEmail;
    public $newEmail;
    public $changedBy;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $oldEmail, string $newEmail, User $changedBy)
    {
        $this->user = $user;
        $this->oldEmail = $oldEmail;
        $this->newEmail = $newEmail;
        $this->changedBy = $changedBy;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $centerName = SettingHelper::get('center_name', 'Hệ thống quản lý học tập');
        
        return new Envelope(
            subject: 'Thông báo thay đổi địa chỉ email - ' . $centerName,
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
            view: 'emails.email-change',
            with: [
                'userName' => $this->user->name,
                'oldEmail' => $this->oldEmail,
                'newEmail' => $this->newEmail,
                'changedByUsername' => $this->changedBy->name,
                'changeDate' => now()->format('d/m/Y H:i'),
                'centerName' => SettingHelper::get('center_name', 'Hệ thống quản lý học tập'),
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
