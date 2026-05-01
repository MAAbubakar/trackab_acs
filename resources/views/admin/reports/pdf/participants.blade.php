<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Participants Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
    </style>
</head>
<body>
    <h2>Participants Report</h2>

    <table>
        <thead>
            <tr>
                <th>Participant No</th>
                <th>Full Name</th>
                <th>Course</th>
                <th>Batch</th>
                <th>Phone</th>
                <th>Daily Summaries</th>
                <th>Average Attendance %</th>
            </tr>
        </thead>
        <tbody>
            @foreach($participants as $participant)
                <tr>
                    <td>{{ $participant->participant_no }}</td>
                    <td>{{ $participant->full_name }}</td>
                    <td>{{ $participant->course?->title ?? 'N/A' }}</td>
                    <td>{{ $participant->batch?->name ?? 'N/A' }}</td>
                    <td>{{ $participant->phone ?: 'N/A' }}</td>
                    <td>{{ $participant->dailySummaries->count() }}</td>
                    <td>{{ $participant->dailySummaries->count() ? number_format($participant->dailySummaries->avg('attendance_percentage'), 2) : '0.00' }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
