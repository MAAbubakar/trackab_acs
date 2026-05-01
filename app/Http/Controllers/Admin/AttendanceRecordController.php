<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use Illuminate\View\View;

class AttendanceRecordController extends Controller
{
    public function index(): View
    {
        $records = AttendanceRecord::with(['participant', 'session', 'checkpoint'])
            ->latest('scan_time')
            ->paginate(20);

        return view('admin.attendance-records.index', compact('records'));
    }
}
