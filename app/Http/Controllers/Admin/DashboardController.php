<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceCheckpoint;
use App\Models\AttendanceDailySummary;
use App\Models\AttendanceFlag;
use App\Models\AttendanceRecord;
use App\Models\Batch;
use App\Models\CertificateEligibility;
use App\Models\Participant;
use App\Models\TrainingSession;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'participants' => Participant::count(),
            'batches' => Batch::count(),
            'sessions' => TrainingSession::count(),
            'open_checkpoints' => AttendanceCheckpoint::where('status', 'open')->count(),
            'attendance_records' => AttendanceRecord::count(),
            'daily_summaries' => AttendanceDailySummary::count(),
            'attendance_flags' => AttendanceFlag::count(),
            'eligible_certificates' => CertificateEligibility::where('eligible', true)->count(),
        ];

        return view('admin.dashboard.index', compact('stats'));
    }
}
