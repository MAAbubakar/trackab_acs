<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CertificateEligibilityExport;
use App\Exports\ParticipantsReportExport;
use App\Http\Controllers\Controller;
use App\Models\AttendanceFlag;
use App\Models\CertificateEligibility;
use App\Models\Participant;
use App\Models\TrainingSession;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('admin.reports.index');
    }

    public function participants(\Illuminate\Http\Request $request): View
    {
        $batchId = $request->integer('batch_id');

        $batches = \App\Models\Batch::with('course')
            ->orderBy('name')
            ->get();

        $participantsQuery = Participant::with(['batch.course', 'course', 'dailySummaries']);

        if ($batchId) {
            $participantsQuery->where('batch_id', $batchId);
        }

        $participants = $participantsQuery
            ->orderBy('full_name')
            ->get();

        return view('admin.reports.participants', compact('participants', 'batches', 'batchId'));
    }

    public function sessions(): View
    {
        $sessions = TrainingSession::with(['course', 'batch', 'venue', 'checkpoints'])
            ->orderByDesc('session_date')
            ->paginate(20);

        return view('admin.reports.sessions', compact('sessions'));
    }

    public function flags(): View
    {
        $flags = AttendanceFlag::with(['participant', 'session', 'checkpoint'])
            ->latest()
            ->paginate(20);

        return view('admin.reports.flags', compact('flags'));
    }

    public function certificates(): View
    {
        $eligibilities = CertificateEligibility::with(['participant', 'course', 'batch'])
            ->latest()
            ->paginate(20);

        return view('admin.reports.certificates', compact('eligibilities'));
    }

    public function participantsExcel(): BinaryFileResponse
    {
        return Excel::download(new ParticipantsReportExport, 'participants-report.xlsx');
    }

    public function certificatesExcel(): BinaryFileResponse
    {
        return Excel::download(new CertificateEligibilityExport, 'certificate-eligibility-report.xlsx');
    }

    public function participantsPdf(\Illuminate\Http\Request $request)
    {
        $batchId = $request->integer('batch_id');

        $query = Participant::with(['batch.course', 'course', 'dailySummaries']);

        if ($batchId) {
            $query->where('batch_id', $batchId);
        }

        $participants = $query
            ->orderBy('full_name')
            ->get();

        $pdf = Pdf::loadView('admin.reports.pdf.participants', compact('participants'));

        return $pdf->download('participants-report.pdf');
    }

    public function certificatesPdf()
    {
        $eligibilities = CertificateEligibility::with(['participant', 'course', 'batch'])
            ->latest()
            ->get();

        $pdf = Pdf::loadView('admin.reports.pdf.certificates', compact('eligibilities'));

        return $pdf->download('certificate-eligibility-report.pdf');
    }


    public function evaluationCompletion(): \Illuminate\View\View
    {
        $batches = \App\Models\Batch::with(['course', 'participants'])
            ->orderBy('name')
            ->get()
            ->map(function ($batch) {
                $participants = $batch->participants;
                $total = $participants->count();
                $submitted = $participants->where('evaluation_completed', true)->count();
                $pending = max($total - $submitted, 0);
                $completionRate = $total > 0 ? round(($submitted / $total) * 100, 1) : 0;

                $batch->evaluation_stats = [
                    'total' => $total,
                    'submitted' => $submitted,
                    'pending' => $pending,
                    'completion_rate' => $completionRate,
                ];

                return $batch;
            });

        $overallStats = [
            'total' => $batches->sum(fn ($batch) => $batch->evaluation_stats['total']),
            'submitted' => $batches->sum(fn ($batch) => $batch->evaluation_stats['submitted']),
            'pending' => $batches->sum(fn ($batch) => $batch->evaluation_stats['pending']),
        ];
        $overallStats['completion_rate'] = $overallStats['total'] > 0
            ? round(($overallStats['submitted'] / $overallStats['total']) * 100, 1)
            : 0;

        return view('admin.reports.evaluation-completion', compact('batches', 'overallStats'));
    }


    public function evaluationCompletionExportExcel()
    {
        $rows = \App\Models\Batch::with(['course', 'participants'])
            ->orderBy('name')
            ->get()
            ->map(function ($batch) {
                $participants = $batch->participants;
                $total = $participants->count();
                $submitted = $participants->where('evaluation_completed', true)->count();
                $pending = max($total - $submitted, 0);
                $completionRate = $total > 0 ? round(($submitted / $total) * 100, 1) : 0;

                return [
                    'Batch' => $batch->name,
                    'Course' => $batch->course?->title ?? '—',
                    'Total Participants' => $total,
                    'Submitted' => $submitted,
                    'Pending' => $pending,
                    'Completion Rate (%)' => $completionRate,
                ];
            });

        $filename = 'evaluation_completion_report_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');

            if ($rows->isNotEmpty()) {
                fputcsv($handle, array_keys($rows->first()));
                foreach ($rows as $row) {
                    fputcsv($handle, $row);
                }
            } else {
                fputcsv($handle, ['Batch', 'Course', 'Total Participants', 'Submitted', 'Pending', 'Completion Rate (%)']);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function evaluationCompletionExportPdf()
    {
        $batches = \App\Models\Batch::with(['course', 'participants'])
            ->orderBy('name')
            ->get()
            ->map(function ($batch) {
                $participants = $batch->participants;
                $total = $participants->count();
                $submitted = $participants->where('evaluation_completed', true)->count();
                $pending = max($total - $submitted, 0);
                $completionRate = $total > 0 ? round(($submitted / $total) * 100, 1) : 0;

                $batch->evaluation_stats = [
                    'total' => $total,
                    'submitted' => $submitted,
                    'pending' => $pending,
                    'completion_rate' => $completionRate,
                ];

                return $batch;
            });

        return view('admin.reports.evaluation-completion-pdf', compact('batches'));
    }

    public function evaluationCompletionBatchDetails(\Illuminate\Http\Request $request, \App\Models\Batch $batch): \Illuminate\View\View
    {
        $status = $request->string('status')->toString();

        $participants = $batch->participants()
            ->with(['course', 'batch'])
            ->when($status === 'submitted', fn ($q) => $q->where('evaluation_completed', true))
            ->when($status === 'pending', fn ($q) => $q->where(function ($query) {
                $query->whereNull('evaluation_completed')
                      ->orWhere('evaluation_completed', false);
            }))
            ->orderBy('full_name')
            ->paginate(30)
            ->withQueryString();

        $stats = [
            'total' => $batch->participants()->count(),
            'submitted' => $batch->participants()->where('evaluation_completed', true)->count(),
        ];
        $stats['pending'] = max($stats['total'] - $stats['submitted'], 0);
        $stats['completion_rate'] = $stats['total'] > 0 ? round(($stats['submitted'] / $stats['total']) * 100, 1) : 0;

        return view('admin.reports.evaluation-completion-batch-details', compact('batch', 'participants', 'stats', 'status'));
    }

}
