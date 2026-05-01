<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\EvaluationForm;
use App\Models\EvaluationSubmission;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EvaluationResponseController extends Controller
{
    public function index(Request $request): View
    {
        $batchId = $request->integer('batch_id');
        $formId = $request->integer('form_id');

        $batches = Batch::with('course')->orderByDesc('id')->get();
        $forms = EvaluationForm::with('batch')->orderByDesc('id')->get();

        $submittedQuery = EvaluationSubmission::query()
            ->with(['participant.batch', 'participant.course', 'form', 'batch'])
            ->when($batchId, fn ($q) => $q->where('batch_id', $batchId))
            ->when($formId, fn ($q) => $q->where('evaluation_form_id', $formId))
            ->latest('submitted_at');

        $submitted = $submittedQuery->paginate(20, ['*'], 'submitted_page');

        $submittedParticipantIds = EvaluationSubmission::query()
            ->when($batchId, fn ($q) => $q->where('batch_id', $batchId))
            ->when($formId, fn ($q) => $q->where('evaluation_form_id', $formId))
            ->pluck('participant_id');

        $pendingQuery = Participant::query()
            ->with(['batch.course', 'course', 'certificateEligibility'])
            ->when($batchId, fn ($q) => $q->where('batch_id', $batchId))
            ->when($submittedParticipantIds->isNotEmpty(), fn ($q) => $q->whereNotIn('id', $submittedParticipantIds))
            ->orderBy('full_name');

        $pending = $pendingQuery->paginate(20, ['*'], 'pending_page');

        $blockedQuery = Participant::query()
            ->with(['batch.course', 'course', 'certificateEligibility'])
            ->whereHas('certificateEligibility', function ($q) {
                $q->where('evaluation_required', true)
                  ->where('evaluation_completed', false);
            })
            ->when($batchId, fn ($q) => $q->where('batch_id', $batchId))
            ->orderBy('full_name');

        $blocked = $blockedQuery->paginate(20, ['*'], 'blocked_page');

        $stats = [
            'submitted_count' => EvaluationSubmission::query()
                ->when($batchId, fn ($q) => $q->where('batch_id', $batchId))
                ->when($formId, fn ($q) => $q->where('evaluation_form_id', $formId))
                ->count(),

            'pending_count' => Participant::query()
                ->when($batchId, fn ($q) => $q->where('batch_id', $batchId))
                ->when($submittedParticipantIds->isNotEmpty(), fn ($q) => $q->whereNotIn('id', $submittedParticipantIds))
                ->count(),

            'blocked_count' => Participant::query()
                ->whereHas('certificateEligibility', function ($q) {
                    $q->where('evaluation_required', true)
                      ->where('evaluation_completed', false);
                })
                ->when($batchId, fn ($q) => $q->where('batch_id', $batchId))
                ->count(),
        ];

        return view('admin.evaluation_responses.index', compact(
            'batches',
            'forms',
            'submitted',
            'pending',
            'blocked',
            'stats',
            'batchId',
            'formId'
        ));
    }
}
