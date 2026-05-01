<?php

namespace App\Notifications;

use App\Models\AttendanceRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AttendanceCapturedNotification extends Notification
{
    use Queueable;

    public function __construct(public AttendanceRecord $record)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'attendance_captured',
            'attendance_record_id' => $this->record->id,
            'training_session_id' => $this->record->training_session_id,
            'attendance_checkpoint_id' => $this->record->attendance_checkpoint_id,
            'scan_time' => optional($this->record->scan_time)->toDateTimeString(),
            'status' => $this->record->status,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Attendance Captured Successfully')
            ->line('Your attendance has been captured successfully.')
            ->line('Scan Time: '.optional($this->record->scan_time)->format('d M Y h:i A'))
            ->action('Open My Dashboard', url('/participant/dashboard'));
    }
}
