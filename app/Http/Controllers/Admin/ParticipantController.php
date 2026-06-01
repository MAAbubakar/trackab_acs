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


    public function downloadImportTemplate()
    {
        $filename = 'trackb_participants_bulk_import_template.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $columns = [
            'participant_no',
            'full_name',
            'email',
            'phone',
            'password',
            'gender',
            'nationality',
            'age',
            'academic_background',
            'employment_status',
            'organization',
            'designation',
            'employment_sector',
            'state_of_origin',
            'lga',
            'course_code',
            'batch_code',
            'registration_status',
            'evaluation_status',
            'notes',
        ];

        $sampleRows = [
            [
                'TRKB-001',
                'Amina Yusuf Bello',
                'amina@example.com',
                '08031234567',
                'Spesse@2026',
                'Female',
                'Nigeria',
                '35',
                'M.Sc/Masters',
                'employed',
                'Ahmadu Bello University',
                'Procurement Officer',
                'Public',
                'Kaduna',
                'Zaria',
                'PROC-MGT',
                'BATCH-2026-01',
                'registered',
                'pending',
                'Sample row. Delete before real upload.',
            ],
            [
                'TRKB-002',
                'Musa Abdullahi Sani',
                'musa@example.com',
                '08039876543',
                'Spesse@2026',
                'Male',
                'Nigeria',
                '42',
                'B.Sc',
                'self-employed',
                'Musa Sani Enterprises',
                'Managing Director',
                'Private',
                'Kano',
                'Nassarawa',
                'PROC-MGT',
                'BATCH-2026-01',
                'registered',
                'pending',
                'Sample row. Delete before real upload.',
            ],
            [
                'TRKB-003',
                'Grace Okafor',
                'grace@example.com',
                '08035551234',
                'Spesse@2026',
                'Female',
                'Nigeria',
                '29',
                'HND',
                'unemployed',
                '',
                '',
                '',
                'Enugu',
                'Enugu North',
                'PROC-MGT',
                'BATCH-2026-01',
                'registered',
                'pending',
                'Sample row. Delete before real upload.',
            ],
        ];

        return response()->streamDownload(function () use ($columns, $sampleRows) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, $columns);

            foreach ($sampleRows as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, $filename, $headers);
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
