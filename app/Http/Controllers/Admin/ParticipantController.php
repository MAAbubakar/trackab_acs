<?php

namespace App\Http\Controllers\Admin;

use App\Support\PhoneHelper;

use App\Http\Controllers\Controller;
use App\Http\Requests\Participant\BulkImportParticipantsRequest;
use App\Http\Requests\Participant\StoreParticipantRequest;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Participant;
use App\Services\ParticipantImportService;
use App\Services\ParticipantQrService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ParticipantController extends Controller
{
    public function __construct(
        private readonly ParticipantQrService $participantQrService,
        private readonly ParticipantImportService $participantImportService
    ) {
    }

    public function index(): View
    {
        $participants = Participant::with(['batch.course', 'course', 'user'])
            ->orderBy('batch_id')
            ->orderBy('full_name')
            ->get()
            ->groupBy(function ($participant) {
                return $participant->batch?->id ?? 'unassigned';
            });

        return view('admin.participants.index', compact('participants'));
    }

    public function create(): View
    {
        $courses = Course::orderBy('title')->get();
        $batches = Batch::with('course')->orderBy('name')->get();

        return view('admin.participants.create', compact('courses', 'batches'));
    }

    public function store(StoreParticipantRequest $request): RedirectResponse
    {
        $payload = array_merge($request->validated(), [
            'age' => $request->input('age'),
            'nationality' => $request->input('nationality'),
            'academic_background' => $request->input('academic_background'),
            'employment_status' => $request->input('employment_status'),
            'employment_sector' => $request->input('employment_sector'),
            'employer_name' => $request->input('employer_name'),
        ]);

        $participant = Participant::create($payload);
        $this->participantQrService->generateQrImage($participant);

        return redirect()->route('admin.participants.index')
            ->with('success', 'Participant created successfully.');
    }

    public function show(Participant $participant): View
    {
        $participant->load(['course', 'batch']);

        return view('admin.participants.show', compact('participant'));
    }

    public function edit(Participant $participant): View
    {
        $courses = Course::orderBy('title')->get();
        $batches = Batch::with('course')->orderBy('name')->get();

        return view('admin.participants.edit', compact('participant', 'courses', 'batches'));
    }

    public function update(StoreParticipantRequest $request, Participant $participant): RedirectResponse
    {
        $payload = array_merge($request->validated(), [
            'age' => $request->input('age'),
            'nationality' => $request->input('nationality'),
            'academic_background' => $request->input('academic_background'),
            'employment_status' => $request->input('employment_status'),
            'employment_sector' => $request->input('employment_sector'),
            'employer_name' => $request->input('employer_name'),
        ]);

        $participant->update($payload);

        return redirect()->route('admin.participants.index')
            ->with('success', 'Participant updated successfully.');
    }

    public function destroy(Participant $participant): RedirectResponse
    {
        $participant->delete();

        return redirect()->route('admin.participants.index')
            ->with('success', 'Participant deleted successfully.');
    }

    public function qrCard(Participant $participant): View
    {
        $participant = $this->participantQrService->generateQrImage($participant);
        $participant->load(['course', 'batch']);

        return view('admin.participants.qr-card', compact('participant'));
    }

    public function regenerateQr(Participant $participant): RedirectResponse
    {
        $participant->update([
            'qr_identifier' => null,
            'qr_code_path' => null,
        ]);

        $this->participantQrService->generateQrImage($participant);

        return redirect()->route('admin.participants.qr-card', $participant)
            ->with('success', 'Participant QR regenerated successfully.');
    }

    public function importForm(): View
    {
        $courses = Course::orderBy('title')->get();
        $batches = Batch::with('course')->orderBy('name')->get();

        return view('admin.participants.import', compact('courses', 'batches'));
    }

    public function import(BulkImportParticipantsRequest $request): RedirectResponse
    {
        $uploadedFile = $request->file('file');

        if (!$uploadedFile || !$uploadedFile->isValid()) {
            return back()->withInput()->with('import_errors', [
                'No valid file was uploaded.',
            ]);
        }

        $result = $this->participantImportService->import(
            $uploadedFile->getRealPath(),
            (int) $request->course_id,
            (int) $request->batch_id
        );

        if (!empty($result['errors'])) {
            return back()
                ->withInput()
                ->with('import_errors', $result['errors'])
                ->with('success', "Import completed. Inserted: {$result['inserted']}, Updated: {$result['updated']}, Skipped: {$result['skipped']}");
        }

        return redirect()->route('admin.participants.index')
            ->with('success', "Import completed. Inserted: {$result['inserted']}, Updated: {$result['updated']}, Skipped: {$result['skipped']}");
    }
}
