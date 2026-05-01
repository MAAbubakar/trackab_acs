<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Batch\StoreBatchRequest;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Venue;
use App\Services\ParticipantQrService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BatchController extends Controller
{
    public function __construct(private readonly ParticipantQrService $participantQrService)
    {
    }

    public function index(): View
    {
        $batches = Batch::with(['course', 'venue'])
            ->latest()
            ->paginate(20);

        return view('admin.batches.index', compact('batches'));
    }

    public function create(): View
    {
        $courses = Course::orderBy('title')->get();
        $venues = Venue::orderBy('name')->get();

        return view('admin.batches.create', compact('courses', 'venues'));
    }

    public function store(StoreBatchRequest $request): RedirectResponse
    {
        Batch::create($request->validated());

        return redirect()
            ->route('admin.batches.index')
            ->with('success', 'Batch created successfully.');
    }

    public function show(Batch $batch): View
    {
        $batch->load(['course', 'venue', 'participants']);

        return view('admin.batches.show', compact('batch'));
    }

    public function edit(Batch $batch): View
    {
        $courses = Course::orderBy('title')->get();
        $venues = Venue::orderBy('name')->get();

        return view('admin.batches.edit', compact('batch', 'courses', 'venues'));
    }

    public function update(StoreBatchRequest $request, Batch $batch): RedirectResponse
    {
        $batch->update($request->validated());

        return redirect()
            ->route('admin.batches.index')
            ->with('success', 'Batch updated successfully.');
    }

    public function destroy(Batch $batch): RedirectResponse
    {
        $batch->delete();

        return redirect()
            ->route('admin.batches.index')
            ->with('success', 'Batch deleted successfully.');
    }

    public function qrCards(Batch $batch): View
    {
        $batch->load('course');

        $participants = $batch->participants()
            ->orderBy('participant_no')
            ->paginate(24);

        foreach ($participants as $participant) {
            $this->participantQrService->generateQrImage($participant);
        }

        $participants->loadMissing([]);

        return view('admin.batches.qr-cards', compact('batch', 'participants'));
    }
}
