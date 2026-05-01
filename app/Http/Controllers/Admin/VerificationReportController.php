<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DliA2aVerificationExport;
use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Participant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class VerificationReportController extends Controller
{
    public function index(Request $request): View
    {
        $batchId = $request->integer('batch_id');
        $batches = Batch::with(['course', 'venue'])->orderByDesc('id')->get();

        $participants = collect();
        $summary = [];
        $batch = null;

        if ($batchId) {
            $batch = Batch::with(['course', 'venue'])->findOrFail($batchId);

            $participants = Participant::query()
                ->with([
                    'batch.course',
                    'course',
                    'evaluationSubmissions.answers.question',
                    'dailySummaries',
                ])
                ->where('batch_id', $batchId)
                ->orderBy('full_name')
                ->get();

            $summary = $this->buildSummary($participants);
        }

        return view('admin.reports.verification.dli_a2a', compact(
            'batches',
            'batchId',
            'batch',
            'participants',
            'summary'
        ));
    }

    public function exportExcel(Request $request): BinaryFileResponse
    {
        $batchId = $request->integer('batch_id');
        abort_unless($batchId, 404);

        $filename = 'dli_a2a_verification_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new DliA2aVerificationExport($batchId), $filename);
    }

    public function exportPdf(Request $request)
    {
        $batchId = $request->integer('batch_id');
        abort_unless($batchId, 404);

        $batch = Batch::with(['course', 'venue'])->findOrFail($batchId);

        $participants = Participant::query()
            ->with([
                'batch.course',
                'course',
                'evaluationSubmissions.answers.question',
                'dailySummaries',
            ])
            ->where('batch_id', $batchId)
            ->orderBy('full_name')
            ->get();

        $summary = $this->buildSummary($participants);

        $pdf = Pdf::loadView('admin.reports.verification.dli_a2a_pdf', compact(
            'batch',
            'participants',
            'summary'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('dli_a2a_verification_' . now()->format('Ymd_His') . '.pdf');
    }

    protected function buildSummary($participants): array
    {
        $total = $participants->count();

        $ratingMap = [
            'Very Satisfied' => 0,
            'Satisfied' => 0,
            'Neutral' => 0,
            'Not Satisfied' => 0,
            'Very dissatisfied' => 0,
        ];

        foreach ($participants as $participant) {
            $rating = $this->resolveCourseRating($participant);
            if ($rating && array_key_exists($rating, $ratingMap)) {
                $ratingMap[$rating]++;
            }
        }

        return [
            'total' => $total,
            'female' => $participants->where('gender', 'female')->count(),
            'male' => $participants->where('gender', 'male')->count(),
            'employed' => $participants->where('employment_status', 'employed')->count(),
            'unemployed' => $participants->where('employment_status', 'unemployed')->count(),
            'public_sector' => $participants->where('employment_sector', 'public')->count(),
            'private_sector' => $participants->where('employment_sector', 'private')->count(),
            'nigerian' => $participants->filter(fn ($p) => strtolower((string) $p->nationality) === 'nigerian')->count(),
            'foreign' => $participants->filter(fn ($p) => $p->nationality && strtolower((string) $p->nationality) !== 'nigerian')->count(),
            'academic_bachelor_diploma' => $participants->filter(function ($p) {
                $v = strtolower((string) $p->academic_background);
                return str_contains($v, 'bachelor') || str_contains($v, 'diploma') || str_contains($v, 'hnd') || str_contains($v, 'b.sc');
            })->count(),
            'academic_masters_phd' => $participants->filter(function ($p) {
                $v = strtolower((string) $p->academic_background);
                return str_contains($v, 'masters') || str_contains($v, 'phd') || str_contains($v, 'm.sc') || str_contains($v, 'doctor');
            })->count(),
            'academic_no_tertiary' => $participants->filter(function ($p) {
                $v = strtolower((string) $p->academic_background);
                return str_contains($v, 'secondary') || str_contains($v, 'none') || str_contains($v, 'no tertiary');
            })->count(),
            'ratings' => $ratingMap,
            'rating_percentages' => collect($ratingMap)->map(
                fn ($count) => $total > 0 ? round(($count / $total) * 100, 2) : 0
            )->toArray(),
        ];
    }

    public function resolveModuleAttended(Participant $participant): string
    {
        return $participant->course?->title
            ?? $participant->batch?->course?->title
            ?? 'N/A';
    }

    public function resolveHoursAttended(Participant $participant): float
    {
        if ($participant->relationLoaded('dailySummaries') && $participant->dailySummaries->count() > 0) {
            return round(
                $participant->dailySummaries->sum(function ($row) {
                    return (float) ($row->attendance_percentage ?? 0);
                }) / 100 * 8,
                2
            );
        }

        return 0.0;
    }

    public function resolveCourseRating(Participant $participant): ?string
    {
        $submission = $participant->evaluationSubmissions->sortByDesc('id')->first();
        if (!$submission) {
            return null;
        }

        $answer = $submission->answers->first(function ($answer) {
            $text = strtolower((string) ($answer->question?->question_text ?? ''));
            return str_contains($text, 'overall') && str_contains($text, 'satisfied');
        });

        return $answer?->answer_option ?: null;
    }
}
