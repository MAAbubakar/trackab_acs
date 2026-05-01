<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\TrainingSession;
use App\Models\Venue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrainingSessionController extends Controller
{
    public function index(Request $request): View
    {
        $batchId = $request->input('batch_id');
        $venueId = $request->input('venue_id');
        $status = $request->input('status');

        $sessions = TrainingSession::with(['course', 'batch.course', 'venue'])
            ->when($batchId, fn ($q) => $q->where('batch_id', $batchId))
            ->when($venueId, fn ($q) => $q->where('venue_id', $venueId))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $matchedCount = $sessions->total();

        $batches = Batch::with('course')
            ->orderBy('name')
            ->get();

        $venues = Venue::orderBy('name')->get();

        $statuses = [
            'scheduled' => 'Scheduled',
            'active' => 'Active',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];

        return view('admin.sessions.index', compact(
            'sessions',
            'matchedCount',
            'batches',
            'venues',
            'statuses',
            'batchId',
            'venueId',
            'status'
        ));
    }

    public function create(): View
    {
        $courses = \App\Models\Course::orderBy('title')->get();
        $batches = Batch::with('course')->orderBy('name')->get();
        $venues = Venue::orderBy('name')->get();

        return view('admin.sessions.create', compact('courses', 'batches', 'venues'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'course_id' => ['nullable', 'exists:courses,id'],
            'batch_id' => ['nullable', 'exists:batches,id'],
            'venue_id' => ['nullable', 'exists:venues,id'],
            'session_date' => ['nullable', 'date'],
            'date' => ['nullable', 'date'],
            'start_time' => ['nullable'],
            'end_time' => ['nullable'],
            'status' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
        ]);

        TrainingSession::create($validated);

        return redirect()->route('admin.sessions.index')
            ->with('success', 'Training session created successfully.');
    }

    public function show(TrainingSession $session): View
    {
        $session->load(['course', 'batch.course', 'venue']);

        return view('admin.sessions.show', compact('session'));
    }

    public function edit(TrainingSession $session): View
    {
        $courses = \App\Models\Course::orderBy('title')->get();
        $batches = Batch::with('course')->orderBy('name')->get();
        $venues = Venue::orderBy('name')->get();

        return view('admin.sessions.edit', compact('session', 'courses', 'batches', 'venues'));
    }

    public function update(Request $request, TrainingSession $session): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'course_id' => ['nullable', 'exists:courses,id'],
            'batch_id' => ['nullable', 'exists:batches,id'],
            'venue_id' => ['nullable', 'exists:venues,id'],
            'session_date' => ['nullable', 'date'],
            'date' => ['nullable', 'date'],
            'start_time' => ['nullable'],
            'end_time' => ['nullable'],
            'status' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
        ]);

        $session->update($validated);

        return redirect()->route('admin.sessions.index')
            ->with('success', 'Training session updated successfully.');
    }

    public function destroy(TrainingSession $session): RedirectResponse
    {
        $session->delete();

        return redirect()->route('admin.sessions.index')
            ->with('success', 'Training session deleted successfully.');
    }
}
