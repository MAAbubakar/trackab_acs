<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate Eligibility Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
    </style>
</head>
<body>
    <h2>Certificate Eligibility Report</h2>

    <table>
        <thead>
            <tr>
                <th>Participant</th>
                <th>Course</th>
                <th>Batch</th>
                <th>Attendance %</th>
                <th>Partial Days</th>
                <th>Absent Days</th>
                <th>Eligible</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody>
            @foreach($eligibilities as $eligibility)
                <tr>
                    <td>{{ $eligibility->participant?->full_name ?? 'N/A' }}</td>
                    <td>{{ $eligibility->course?->title ?? 'N/A' }}</td>
                    <td>{{ $eligibility->batch?->name ?? 'N/A' }}</td>
                    <td>{{ $eligibility->attendance_percentage }}%</td>
                    <td>{{ $eligibility->partial_days }}</td>
                    <td>{{ $eligibility->absent_days }}</td>
                    <td>{{ $eligibility->eligible ? 'Yes' : 'No' }}</td>
                    <td>{{ $eligibility->reason ?: 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
