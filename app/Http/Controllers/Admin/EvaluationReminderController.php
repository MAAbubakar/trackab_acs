<?php

namespace App\Http\Controllers\Admin;

use App\Exports\EvaluationReminderExport;
use App\Http\Controllers\Controller;
use App\Jobs\SendEvaluationReminderJob;
use App\Jobs\SendEvaluationReminderSmsJob;
use App\Models\Batch;
use App\Models\MessageLog;
use App\Models\Notification;
use App\Models\Participant;
use App\Support\PhoneHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class EvaluationReminderController extends Controller
{
    public function index(Request $request): View
    {
        $batchId = $request->integer('batch_id');
        $status = $request->get('status', 'pending');

        $batches = Batch::with('course')->orderByDesc('id')->get();

        $query = Participant::query()
            ->with(['batch.course', 'course', 'certificateEligibility'])
            ->when($batchId, fn ($q) => $q->where('batch_id', $batchId));

        if ($status === 'blocked') {
            $query->whereHas('certificateEligibility', function ($q) {
                $q->where('evaluation_required', true)
                  ->where('evaluation_completed', false);
            });
        } else {
            $query->where(function ($q) {
                $q->where('evaluation_completed', false)
                  ->orWhereNull('evaluation_completed');
            });
        }

        $participants = $query->orderBy('full_name')->paginate(25);

        return view('admin.evaluation_reminders.index', compact(
            'participants',
            'batches',
            'batchId',
            'status'
        ));
    }

    public function sendBatch(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole('super-admin'), 403);

        $data = $request->validate([
            'batch_id' => ['nullable', 'exists:batches,id'],
            'status' => ['required', 'in:pending,blocked'],
        ]);

        $query = Participant::query()
            ->with(['batch.course', 'course', 'certificateEligibility', 'user'])
            ->when($data['batch_id'] ?? null, fn ($q, $batchId) => $q->where('batch_id', $batchId));

        if ($data['status'] === 'blocked') {
            $query->whereHas('certificateEligibility', function ($q) {
                $q->where('evaluation_required', true)
                  ->where('evaluation_completed', false);
            });
        } else {
            $query->where(function ($q) {
                $q->where('evaluation_completed', false)
                  ->orWhereNull('evaluation_completed');
            });
        }

        $participants = $query->get();

        $queuedEmailCount = 0;
        $queuedSmsCount = 0;
        $skippedCount = 0;

        foreach ($participants as $participant) {
            $message = $this->buildReminderMessage($participant);

            if (class_exists(Notification::class)) {
                Notification::create([
                    'user_id' => $participant->user_id,
                    'title' => 'Evaluation Reminder',
                    'message' => $message,
                    'type' => 'evaluation_reminder',
                    'status' => 'unread',
                ]);
            }

            if (!empty($participant->email)) {
                $messageLog = MessageLog::create([
                    'user_id' => $participant->user_id,
                    'participant_id' => $participant->id,
                    'message_type' => 'evaluation_reminder',
                    'channel' => 'email',
                    'subject' => 'Training Evaluation Reminder',
                    'body' => $message,
                    'status' => 'queued',
                    'metadata' => [
                        'email' => $participant->email,
                        'phone' => $participant->phone,
                        'normalized_phone' => PhoneHelper::normalizeNigeria($participant->phone),
                        'batch' => $participant->batch?->name,
                        'course' => $participant->course?->title ?? $participant->batch?->course?->title,
                        'queued_by_user_id' => auth()->id(),
                    ],
                ]);

                SendEvaluationReminderJob::dispatch(
                    participantId: $participant->id,
                    messageBody: $message,
                    messageLogId: $messageLog->id
                );

                $queuedEmailCount++;
                continue;
            }

            if (!empty($participant->phone)) {
                $messageLog = MessageLog::create([
                    'user_id' => $participant->user_id,
                    'participant_id' => $participant->id,
                    'message_type' => 'evaluation_reminder',
                    'channel' => 'sms',
                    'subject' => 'Training Evaluation Reminder',
                    'body' => $message,
                    'status' => 'queued',
                    'metadata' => [
                        'email' => $participant->email,
                        'phone' => $participant->phone,
                        'normalized_phone' => PhoneHelper::normalizeNigeria($participant->phone),
                        'batch' => $participant->batch?->name,
                        'course' => $participant->course?->title ?? $participant->batch?->course?->title,
                        'queued_by_user_id' => auth()->id(),
                    ],
                ]);

                SendEvaluationReminderSmsJob::dispatch(
                    participantId: $participant->id,
                    messageBody: $message,
                    messageLogId: $messageLog->id
                );

                $queuedSmsCount++;
                continue;
            }

            MessageLog::create([
                'user_id' => $participant->user_id,
                'participant_id' => $participant->id,
                'message_type' => 'evaluation_reminder',
                'channel' => 'system',
                'subject' => 'Training Evaluation Reminder',
                'body' => $message,
                'status' => 'skipped',
                'metadata' => [
                    'email' => $participant->email,
                    'phone' => $participant->phone,
                        'normalized_phone' => PhoneHelper::normalizeNigeria($participant->phone),
                    'batch' => $participant->batch?->name,
                    'course' => $participant->course?->title ?? $participant->batch?->course?->title,
                    'skip_reason' => 'No email address or phone number on participant record.',
                    'queued_by_user_id' => auth()->id(),
                ],
            ]);

            $skippedCount++;
        }

        return redirect()
            ->back()
            ->with(
                'success',
                "Reminder queue created. Email queued: {$queuedEmailCount}, SMS queued: {$queuedSmsCount}, Skipped(no email/phone): {$skippedCount}."
            );
    }

    public function exportExcel(Request $request): BinaryFileResponse
    {
        $batchId = $request->integer('batch_id');
        $status = $request->get('status', 'pending');

        $filename = "evaluation_{$status}_list_" . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new EvaluationReminderExport($batchId, $status), $filename);
    }

    public function exportPdf(Request $request)
    {
        $batchId = $request->integer('batch_id');
        $status = $request->get('status', 'pending');

        $query = Participant::query()
            ->with(['batch.course', 'course', 'certificateEligibility'])
            ->when($batchId, fn ($q) => $q->where('batch_id', $batchId));

        if ($status === 'blocked') {
            $query->whereHas('certificateEligibility', function ($q) {
                $q->where('evaluation_required', true)
                  ->where('evaluation_completed', false);
            });
        } else {
            $query->where(function ($q) {
                $q->where('evaluation_completed', false)
                  ->orWhereNull('evaluation_completed');
            });
        }

        $participants = $query->orderBy('full_name')->get();

        $pdf = Pdf::loadView('admin.evaluation_reminders.pdf', [
            'participants' => $participants,
            'status' => $status,
        ])->setPaper('a4', 'landscape');

        $filename = "evaluation_{$status}_list_" . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }

    protected function buildReminderMessage(Participant $participant): string
    {
        $name = $participant->full_name ?? 'Participant';
        $batch = $participant->batch?->name ?? 'your batch';
        $course = $participant->course?->title ?? $participant->batch?->course?->title ?? 'your training';

        return "Dear {$name}, this is a reminder to complete your evaluation for {$course} ({$batch}). Your certificate readiness depends on completing the evaluation.";
    }
}
