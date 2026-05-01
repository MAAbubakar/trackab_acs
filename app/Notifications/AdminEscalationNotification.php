<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminEscalationNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $subjectLine,
        public string $messageBody,
        public array $payload = []
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'admin_escalation',
            'subject' => $this->subjectLine,
            'body' => $this->messageBody,
            'payload' => $this->payload,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subjectLine)
            ->line($this->messageBody)
            ->action('Open Admin Panel', url('/admin/dashboard'));
    }
}
