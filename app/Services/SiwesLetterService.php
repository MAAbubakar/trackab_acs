<?php

namespace App\Services;

use App\Models\Participant;
use App\Models\SiwesLetter;
use App\Models\SiwesLetterTemplate;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SiwesLetterService
{
    public function getEligibility(Participant $participant): array
    {
        $participant->loadMissing([
            'batch.course',
            'course',
            'latestSiwesLetter',
        ]);

        $track = $participant->batch?->course?->track ?? $participant->course?->track;

        if ($track !== 'Track B') {
            return [
                'eligible' => false,
                'reason' => 'SIWES letters are only available for Track B participants.',
            ];
        }

        if (($participant->status ?? 'active') !== 'active') {
            return [
                'eligible' => false,
                'reason' => 'Participant is not active.',
            ];
        }

        $attendance = $this->resolveAttendanceStatus($participant);
        if (!$attendance['met']) {
            return [
                'eligible' => false,
                'reason' => $attendance['reason'],
            ];
        }

        $evaluation = $this->resolveEvaluationStatus($participant);
        if (!$evaluation['met']) {
            return [
                'eligible' => false,
                'reason' => $evaluation['reason'],
            ];
        }

        $template = $this->getActiveTemplate();
        if (!$template) {
            return [
                'eligible' => false,
                'reason' => 'No active SIWES letter template is configured.',
            ];
        }

        return [
            'eligible' => true,
            'reason' => null,
        ];
    }

    public function issueLetter(Participant $participant, ?int $issuedBy = null, array $overrides = []): SiwesLetter
    {
        $eligibility = $this->getEligibility($participant);

        if (!$eligibility['eligible']) {
            throw new \RuntimeException($eligibility['reason'] ?? 'Participant is not eligible for SIWES letter.');
        }

        $participant->loadMissing(['batch.course', 'course']);

        $template = $this->getActiveTemplate();
        if (!$template) {
            throw new \RuntimeException('No active SIWES letter template is configured.');
        }

        return DB::transaction(function () use ($participant, $template, $issuedBy, $overrides) {
            $existing = SiwesLetter::query()
                ->where('participant_id', $participant->id)
                ->latest('id')
                ->first();

            $issueDate = isset($overrides['issue_date'])
                ? Carbon::parse($overrides['issue_date'])->toDateString()
                : now()->toDateString();

            $siwesStartDate = isset($overrides['siwes_start_date'])
                ? Carbon::parse($overrides['siwes_start_date'])->toDateString()
                : null;

            $siwesEndDate = isset($overrides['siwes_end_date'])
                ? Carbon::parse($overrides['siwes_end_date'])->toDateString()
                : null;

            $payload = [
                'participant_id' => $participant->id,
                'batch_id' => $participant->batch_id,
                'template_id' => $template->id,
                'reference_no' => $existing?->reference_no ?: $this->generateReferenceNo($participant),
                'issue_date' => $issueDate,
                'status' => 'issued',
                'host_organization' => $overrides['host_organization'] ?? $existing?->host_organization,
                'host_address' => $overrides['host_address'] ?? $existing?->host_address,
                'siwes_start_date' => $siwesStartDate ?? $existing?->siwes_start_date,
                'siwes_end_date' => $siwesEndDate ?? $existing?->siwes_end_date,
                'issued_by' => $issuedBy ?? $existing?->issued_by,
            ];

            if ($existing) {
                $existing->update($payload);
                return $existing->fresh(['participant', 'batch', 'template', 'issuer']);
            }

            return SiwesLetter::create($payload)->load(['participant', 'batch', 'template', 'issuer']);
        });
    }

    public function markDownloaded(SiwesLetter $letter): SiwesLetter
    {
        $letter->update([
            'status' => 'downloaded',
            'downloaded_at' => now(),
            'last_printed_at' => now(),
            'print_count' => ((int) $letter->print_count) + 1,
        ]);

        return $letter->fresh();
    }

    public function getActiveTemplate(): ?SiwesLetterTemplate
    {
        return SiwesLetterTemplate::query()
            ->where('is_active', true)
            ->latest('id')
            ->first();
    }

    public function generateReferenceNo(Participant $participant): string
    {
        $year = now()->format('Y');
        $batchPart = $participant->batch_id ?: 'NA';
        $participantPart = $participant->participant_no ?: str_pad((string) $participant->id, 4, '0', STR_PAD_LEFT);

        return sprintf('SPESSE/ABU/SIWES/%s/%s/%s', $year, $batchPart, $participantPart);
    }

    protected function resolveAttendanceStatus(Participant $participant): array
    {
        if (method_exists($participant, 'certificateEligibility') && $participant->relationLoaded('certificateEligibility') === false) {
            $participant->loadMissing('certificateEligibility');
        }

        $certificateEligibility = $participant->certificateEligibility ?? null;

        if ($certificateEligibility) {
            if (($certificateEligibility->attendance_required ?? true) && !($certificateEligibility->attendance_met ?? false)) {
                return [
                    'met' => false,
                    'reason' => 'Attendance requirement has not been met for SIWES eligibility.',
                ];
            }

            return [
                'met' => true,
                'reason' => null,
            ];
        }

        $attendancePercent = $this->resolveAttendancePercent($participant);

        if ($attendancePercent < 80) {
            return [
                'met' => false,
                'reason' => 'Attendance requirement has not been met for SIWES eligibility.',
            ];
        }

        return [
            'met' => true,
            'reason' => null,
        ];
    }

    protected function resolveEvaluationStatus(Participant $participant): array
    {
        if (!empty($participant->evaluation_completed)) {
            return [
                'met' => true,
                'reason' => null,
            ];
        }

        if (method_exists($participant, 'certificateEligibility') && $participant->relationLoaded('certificateEligibility') === false) {
            $participant->loadMissing('certificateEligibility');
        }

        $certificateEligibility = $participant->certificateEligibility ?? null;

        if ($certificateEligibility && ($certificateEligibility->evaluation_required ?? true) && !($certificateEligibility->evaluation_completed ?? false)) {
            return [
                'met' => false,
                'reason' => 'Evaluation must be completed before SIWES letter can be issued.',
            ];
        }

        return [
            'met' => false,
            'reason' => 'Evaluation must be completed before SIWES letter can be issued.',
        ];
    }

    protected function resolveAttendancePercent(Participant $participant): float
    {
        if (method_exists($participant, 'dailySummaries') && $participant->relationLoaded('dailySummaries') === false) {
            $participant->loadMissing('dailySummaries');
        }

        $dailySummaries = $participant->dailySummaries ?? collect();

        if ($dailySummaries->isEmpty()) {
            return 0;
        }

        $possibleColumns = [
            'attendance_percentage',
            'attendance_percent',
            'present_percent',
        ];

        foreach ($possibleColumns as $column) {
            $values = $dailySummaries
                ->map(fn ($row) => $row->{$column} ?? null)
                ->filter(fn ($value) => $value !== null);

            if ($values->isNotEmpty()) {
                return round((float) $values->avg(), 2);
            }
        }

        return 0;
    }
}
