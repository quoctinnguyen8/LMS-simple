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

class AccountDeletionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $deletedBy;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, User $deletedBy)
    {
        $this->user = $user;
        $this->deletedBy = $deletedBy;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $centerName = SettingHelper::get('center_name', 'Hệ thống quản lý học tập');
        
        return new Envelope(
            subject: '[no-reply] Thông báo tài khoản đã bị xóa - ' . $centerName,
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
            view: 'emails.account-deletion',
            with: [
                'userName' => $this->user->name,
                'userEmail' => $this->user->email,
                'deletedByName' => $this->deletedBy->name,
                'deletionDate' => now()->format('d/m/Y H:i'),
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
