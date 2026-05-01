<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Training Evaluation Reminder</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #222;">
    <p>Dear {{ $participant->full_name ?? 'Participant' }},</p>

    <p>{{ $messageBody }}</p>

    <p>
        Please log in to the participant portal and complete your evaluation as soon as possible.
    </p>

    <p>
        Regards,<br>
        SPESSE-CE ABU
    </p>
</body>
</html>
