<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\EvaluationAnswer;
use App\Models\EvaluationForm;
use App\Models\EvaluationSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class EvaluationController extends Controller
{
    public function show(Request $request): View
    {
        $participant = $request->user()->participant;
        abort_unless($participant, 403);

        $form = $this->resolveActiveForm($participant);

        if (!$form) {
            return view('participant.evaluation.unavailable', [
                'title' => 'No Active Evaluation Form Yet',
                'message' => 'There is currently no active evaluation form available for you. Please check back later or contact the administrator.',
            ]);
        }

        $existingSubmission = $this->getExistingSubmission($participant->id, $form->id);

        if ($existingSubmission) {
            return view('participant.evaluation.unavailable', [
                'title' => 'Evaluation Already Submitted',
                'message' => 'You have already submitted your evaluation form on ' . optional($existingSubmission->submitted_at)->format('d M Y h:i A') . '. Thank you for your feedback.',
            ]);
        }

        if ($this->isEvaluationClosed($form)) {
            return view('participant.evaluation.unavailable', [
                'title' => 'Evaluation Period Has Closed',
                'message' => 'This evaluation form is no longer open for submission. Please contact the administrator if you believe this is an error.',
            ]);
        }

        $form->load(['questions' => function ($query) {
            $query->orderBy('sort_order')->orderBy('id');
        }]);

        return view('participant.evaluation.show', compact('participant', 'form', 'existingSubmission'));
    }

    public function submit(Request $request): RedirectResponse|View
    {
        $participant = $request->user()->participant;
        abort_unless($participant, 403);

        $form = $this->resolveActiveForm($participant);

        if (!$form) {
            return view('participant.evaluation.unavailable', [
                'title' => 'No Active Evaluation Form Yet',
                'message' => 'There is currently no active evaluation form available for you. Please check back later or contact the administrator.',
            ]);
        }

        $existingSubmission = $this->getExistingSubmission($participant->id, $form->id);

        if ($existingSubmission) {
            return view('participant.evaluation.unavailable', [
                'title' => 'Evaluation Already Submitted',
                'message' => 'You have already submitted your evaluation form on ' . optional($existingSubmission->submitted_at)->format('d M Y h:i A') . '. Thank you for your feedback.',
            ]);
        }

        if ($this->isEvaluationClosed($form)) {
            return view('participant.evaluation.unavailable', [
                'title' => 'Evaluation Period Has Closed',
                'message' => 'This evaluation form is no longer open for submission. Please contact the administrator if you believe this is an error.',
            ]);
        }

        $form->load(['questions' => function ($query) {
            $query->orderBy('sort_order')->orderBy('id');
        }]);

        $answers = $request->input('answers', []);

        if (!is_array($answers) || empty($answers)) {
            $answers = [];
            foreach ($form->questions as $question) {
                $qid = (string) $question->id;

                if ($request->has($qid)) {
                    $answers[$question->id] = $request->input($qid);
                    continue;
                }

                $altKey = 'question_' . $question->id;
                if ($request->has($altKey)) {
                    $answers[$question->id] = $request->input($altKey);
                }
            }
        }

        if (empty($answers)) {
            return back()
                ->withInput()
                ->withErrors([
                    'answers' => 'Please answer the evaluation questions before submitting.',
                ]);
        }

        $submissionData = [
            'evaluation_form_id' => $form->id,
            'participant_id' => $participant->id,
            'submitted_at' => now(),
        ];

        if ($this->columnExists('evaluation_submissions', 'batch_id')) {
            $submissionData['batch_id'] = $participant->batch_id;
        }

        if ($this->columnExists('evaluation_submissions', 'course_id')) {
            $submissionData['course_id'] = $participant->batch?->course?->id ?? $participant->course_id;
        }

        $submission = EvaluationSubmission::create($submissionData);

        foreach ($form->questions as $question) {
            $answerValue = $answers[$question->id] ?? null;

            if (is_array($answerValue)) {
                $filtered = array_filter($answerValue, fn ($v) => $v !== null && $v !== '');
                $hasValue = count($filtered) > 0;
            } else {
                $hasValue = $answerValue !== null && $answerValue !== '';
            }

            if (!$hasValue) {
                continue;
            }

            EvaluationAnswer::create([
                'evaluation_submission_id' => $submission->id,
                'evaluation_question_id' => $question->id,
                'participant_id' => $participant->id,
                'answer_text' => is_array($answerValue) ? null : $answerValue,
                'answer_option' => is_array($answerValue) ? json_encode(array_values($filtered ?? $answerValue)) : null,
            ]);
        }

        $participant->update([
            'evaluation_completed' => true,
            'evaluation_completed_at' => now(),
        ]);

        return redirect()
            ->route('participant.evaluation.show')
            ->with('success', 'Your evaluation has been submitted successfully.');
    }

    protected function resolveActiveForm($participant): ?EvaluationForm
    {
        $query = EvaluationForm::query();

        if ($this->columnExists('evaluation_forms', 'is_active')) {
            $query->where('is_active', true);
        }

        if ($this->columnExists('evaluation_forms', 'status')) {
            $query->where(function ($q) {
                $q->whereNull('status')
                    ->orWhere('status', 'active')
                    ->orWhere('status', 'published');
            });
        }

        if ($this->columnExists('evaluation_forms', 'batch_id') && !empty($participant->batch_id)) {
            $batchMatch = (clone $query)->where('batch_id', $participant->batch_id)->latest('id')->first();
            if ($batchMatch) {
                return $batchMatch;
            }
        }

        if ($this->columnExists('evaluation_forms', 'course_id')) {
            $courseId = $participant->batch?->course?->id ?? $participant->course_id ?? null;
            if ($courseId) {
                $courseMatch = (clone $query)->where('course_id', $courseId)->latest('id')->first();
                if ($courseMatch) {
                    return $courseMatch;
                }
            }
        }

        return $query->latest('id')->first();
    }

    protected function getExistingSubmission(int $participantId, int $formId): ?EvaluationSubmission
    {
        return EvaluationSubmission::query()
            ->where('participant_id', $participantId)
            ->where('evaluation_form_id', $formId)
            ->latest('id')
            ->first();
    }

    protected function isEvaluationClosed(EvaluationForm $form): bool
    {
        if ($this->columnExists('evaluation_forms', 'status') && in_array($form->status, ['closed', 'inactive'], true)) {
            return true;
        }

        if ($this->columnExists('evaluation_forms', 'end_date') && !empty($form->end_date) && now()->gt($form->end_date)) {
            return true;
        }

        if ($this->columnExists('evaluation_forms', 'closes_at') && !empty($form->closes_at) && now()->gt($form->closes_at)) {
            return true;
        }

        return false;
    }

    protected function columnExists(string $table, string $column): bool
    {
        static $cache = [];

        $key = $table . '.' . $column;

        if (!array_key_exists($key, $cache)) {
            $cache[$key] = Schema::hasColumn($table, $column);
        }

        return $cache[$key];
    }
}
