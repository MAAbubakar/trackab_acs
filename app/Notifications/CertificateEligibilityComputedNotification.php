<?php

namespace App\Notifications;

use App\Models\CertificateEligibility;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CertificateEligibilityComputedNotification extends Notification
{
    use Queueable;

    public function __construct(public CertificateEligibility $eligibility)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'certificate_eligibility_computed',
            'eligibility_id' => $this->eligibility->id,
            'participant_id' => $this->eligibility->participant_id,
            'attendance_percentage' => $this->eligibility->attendance_percentage,
            'eligible' => $this->eligibility->eligible,
            'reason' => $this->eligibility->reason,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Certificate Eligibility Update')
            ->line('Your certificate eligibility has been evaluated.')
            ->line('Attendance Percentage: '.$this->eligibility->attendance_percentage.'%')
            ->line('Eligible: '.($this->eligibility->eligible ? 'Yes' : 'No'))
            ->line('Reason: '.($this->eligibility->reason ?: 'N/A'))
            ->action('Open My Dashboard', url('/participant/dashboard'));
    }
}
