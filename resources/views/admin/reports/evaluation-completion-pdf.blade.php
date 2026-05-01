<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Evaluation Completion Report</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111; margin: 24px; }
        h2 { margin-bottom: 6px; }
        p { margin-top: 0; color: #444; }
        table { width: 100%; border-collapse: collapse; margin-top: 18px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; font-size: 13px; }
        th { background: #f3f4f6; }
    </style>
</head>
<body>
    <h2>Evaluation Completion by Batch</h2>
    <p>Generated on {{ now()->format('d M Y h:i A') }}</p>

    <table>
        <thead>
            <tr>
                <th>Batch</th>
                <th>Course</th>
                <th>Total Participants</th>
                <th>Submitted</th>
                <th>Pending</th>
                <th>Completion Rate</th>
            </tr>
        </thead>
        <tbody>
            @forelse($batches as $batch)
                <tr>
                    <td>{{ $batch->name }}</td>
                    <td>{{ $batch->course?->title ?? '—' }}</td>
                    <td>{{ $batch->evaluation_stats['total'] }}</td>
                    <td>{{ $batch->evaluation_stats['submitted'] }}</td>
                    <td>{{ $batch->evaluation_stats['pending'] }}</td>
                    <td>{{ $batch->evaluation_stats['completion_rate'] }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No batch evaluation data found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
