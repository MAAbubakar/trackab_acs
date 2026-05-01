<?php

namespace App\Mail;

use App\Models\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EvaluationReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Participant $participant,
        public string $messageBody
    ) {
    }

    public function build(): self
    {
        return $this->subject('Training Evaluation Reminder')
            ->view('emails.evaluation_reminder');
    }
}
