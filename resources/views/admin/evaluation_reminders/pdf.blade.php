<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Evaluation Reminder Export</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 6px; text-align: left; vertical-align: top; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2>{{ strtoupper($status) }} Evaluation List</h2>

    <table>
        <thead>
            <tr>
                <th>Participant No</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Batch</th>
                <th>Course</th>
                <th>Evaluation Completed</th>
                <th>Eligibility Status</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody>
            @forelse($participants as $participant)
                <tr>
                    <td>{{ $participant->participant_no ?? '' }}</td>
                    <td>{{ $participant->full_name ?? '' }}</td>
                    <td>{{ $participant->email ?? '' }}</td>
                    <td>{{ $participant->phone ?? '' }}</td>
                    <td>{{ $participant->batch?->name ?? '' }}</td>
                    <td>{{ $participant->course?->title ?? $participant->batch?->course?->title ?? '' }}</td>
                    <td>{{ $participant->evaluation_completed ? 'Yes' : 'No' }}</td>
                    <td>{{ $participant->certificateEligibility?->eligibility_status ?? 'pending' }}</td>
                    <td>{{ $participant->certificateEligibility?->ineligibility_reason ?? '' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">No data available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
