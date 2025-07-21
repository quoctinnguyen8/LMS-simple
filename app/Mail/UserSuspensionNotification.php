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

class UserSuspensionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $suspendedBy;
    public $reason;
    public $action; // 'suspend' hoặc 'reactivate'

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, User $suspendedBy, string $reason, string $action = 'suspend')
    {
        $this->user = $user;
        $this->suspendedBy = $suspendedBy;
        $this->reason = $reason;
        $this->action = $action;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $centerName = SettingHelper::get('center_name', 'Hệ thống quản lý học tập');
        $subject = $this->action === 'suspend' 
            ? 'Thông báo tài khoản bị đình chỉ - ' . $centerName
            : 'Thông báo kích hoạt lại tài khoản - ' . $centerName;
        
        return new Envelope(
            subject: "[no-reply] $subject",
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
            view: 'emails.user-suspension',
            with: [
                'userName' => $this->user->name,
                'userEmail' => $this->user->email,
                'suspendedByUsername' => $this->suspendedBy->name,
                'reason' => $this->reason,
                'action' => $this->action,
                'actionDate' => now()->format('d/m/Y H:i'),
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
