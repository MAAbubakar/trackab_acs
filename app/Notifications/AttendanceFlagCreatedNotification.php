<?php

namespace App\Notifications;

use App\Models\AttendanceFlag;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AttendanceFlagCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(public AttendanceFlag $flag)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'attendance_flag_created',
            'flag_id' => $this->flag->id,
            'flag_type' => $this->flag->flag_type,
            'participant_id' => $this->flag->participant_id,
            'training_session_id' => $this->flag->training_session_id,
            'description' => $this->flag->description,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Attendance Flag Alert')
            ->line('A new attendance flag has been created.')
            ->line('Flag Type: '.str_replace('_', ' ', $this->flag->flag_type))
            ->line('Description: '.($this->flag->description ?: 'N/A'))
            ->action('Open Admin Panel', url('/admin/attendance-flags'));
    }
}
