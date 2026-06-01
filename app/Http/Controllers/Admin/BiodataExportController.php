<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BiodataExportController extends Controller
{
    public function index(Request $request): View
    {
        $batches = Batch::query()
            ->latest()
            ->get();

        $selectedBatch = null;
        $completedCount = null;
        $totalCount = null;

        if ($request->filled('batch_id')) {
            $selectedBatch = Batch::find($request->integer('batch_id'));

            if ($selectedBatch) {
                $participants = Participant::query()
                    ->where('batch_id', $selectedBatch->id)
                    ->get();

                $totalCount = $participants->count();
                $completedCount = $participants
                    ->filter(fn ($participant) => $this->hasCompletedBiodata($participant))
                    ->count();
            }
        }

        return view('admin.reports.completed-biodata', compact(
            'batches',
            'selectedBatch',
            'completedCount',
            'totalCount'
        ));
    }

    public function exportBatch(Request $request, Batch $batch): StreamedResponse
    {
        $participants = Participant::query()
            ->with(['course', 'batch'])
            ->where('batch_id', $batch->id)
            ->orderBy('full_name')
            ->get()
            ->filter(fn ($participant) => $this->hasCompletedBiodata($participant))
            ->values();

        $fileName = 'completed-biodata-' . str($batch->name ?? 'batch')->slug('-') . '-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($participants) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Participant No',
                'Full Name',
                'Email',
                'Phone',
                'Gender',
                'Nationality',
                'State of Origin',
                'LGA',
                'Age',
                'Academic Background',
                'Employment Status',
                'Employment Sector',
                'Organization',
                'Designation',
                'Employer Name',
                'Course',
                'Batch',
                'Status',
                'Created At',
                'Updated At',
            ]);

            foreach ($participants as $participant) {
                fputcsv($handle, [
                    $participant->participant_no ?? '',
                    $participant->full_name ?? '',
                    $participant->email ?? '',
                    $participant->phone ?? '',
                    $participant->gender ?? '',
                    $participant->nationality ?? '',
                    $participant->state_of_origin ?? '',
                    $participant->lga ?? '',
                    $participant->age ?? '',
                    $participant->academic_background ?? '',
                    $participant->employment_status ?? '',
                    $participant->employment_sector ?? '',
                    $participant->organization ?? '',
                    $participant->designation ?? '',
                    $participant->employer_name ?? '',
                    optional($participant->course)->title ?? optional($participant->course)->name ?? '',
                    optional($participant->batch)->name ?? '',
                    $participant->status ?? '',
                    optional($participant->created_at)->format('Y-m-d H:i:s') ?? '',
                    optional($participant->updated_at)->format('Y-m-d H:i:s') ?? '',
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function hasCompletedBiodata(Participant $participant): bool
    {
        $requiredFields = [
            'full_name',
            'email',
            'phone',
            'gender',
            'nationality',
            'state_of_origin',
            'lga',
            'age',
            'academic_background',
            'employment_status',
            'employment_sector',
            'organization',
            'designation',
        ];

        $existingRequiredFields = collect($requiredFields)
            ->filter(fn ($field) => Schema::hasColumn('participants', $field))
            ->values();

        foreach ($existingRequiredFields as $field) {
            $value = $participant->{$field} ?? null;

            if ($value === null || trim((string) $value) === '') {
                return false;
            }
        }

        return true;
    }
}
