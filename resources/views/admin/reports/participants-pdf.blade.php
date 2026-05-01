<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Participants Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 6px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Participants Report @if($batchId) - Batch {{ $batchId }} @endif</h2>

    <table>
        <thead>
            <tr>
                <th>Participant No</th>
                <th>Full Name</th>
                <th>Batch</th>
                <th>Course</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Gender</th>
                <th>Status</th>
                <th>Evaluation</th>
            </tr>
        </thead>
        <tbody>
            @forelse($participants as $participant)
                <tr>
                    <td>{{ $participant->participant_no }}</td>
                    <td>{{ $participant->full_name }}</td>
                    <td>{{ $participant->batch?->name ?? '—' }}</td>
                    <td>{{ $participant->batch?->course?->title ?? $participant->course?->title ?? '—' }}</td>
                    <td>{{ $participant->email ?? '—' }}</td>
                    <td>{{ $participant->phone ?? '—' }}</td>
                    <td>{{ $participant->gender ?? '—' }}</td>
                    <td>{{ $participant->status ?? '—' }}</td>
                    <td>{{ !empty($participant->evaluation_completed) ? 'Completed' : 'Pending' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">No participants found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
