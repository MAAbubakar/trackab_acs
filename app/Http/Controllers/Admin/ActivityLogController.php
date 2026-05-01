<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $logs = ActivityLog::with(['user', 'participant'])
            ->when($request->filled('action'), fn ($q) => $q->where('action', $request->string('action')))
            ->when($request->filled('subject_type'), fn ($q) => $q->where('subject_type', $request->string('subject_type')))
            ->when($request->filled('route_name'), fn ($q) => $q->where('route_name', $request->string('route_name')))
            ->latest()
            ->paginate(30)
            ->withQueryString();

        $actions = ActivityLog::query()->select('action')->distinct()->orderBy('action')->pluck('action');
        $subjectTypes = ActivityLog::query()->select('subject_type')->whereNotNull('subject_type')->distinct()->orderBy('subject_type')->pluck('subject_type');
        $routeNames = ActivityLog::query()->select('route_name')->whereNotNull('route_name')->distinct()->orderBy('route_name')->pluck('route_name');

        return view('admin.activity-logs.index', compact('logs', 'actions', 'subjectTypes', 'routeNames'));
    }
}
