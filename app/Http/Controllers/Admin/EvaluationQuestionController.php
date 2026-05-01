<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EvaluationForm;
use App\Models\EvaluationQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EvaluationQuestionController extends Controller
{
    public function index(EvaluationForm $evaluationForm): View
    {
        $evaluationForm->load(['questions' => fn ($q) => $q->orderBy('sort_order')->orderBy('id')]);

        return view('admin.evaluation_questions.index', compact('evaluationForm'));
    }

    public function create(EvaluationForm $evaluationForm): View
    {
        return view('admin.evaluation_questions.create', compact('evaluationForm'));
    }

    public function store(Request $request, EvaluationForm $evaluationForm): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole('super-admin'), 403);

        $data = $request->validate([
            'section_name' => ['nullable', 'string', 'max:150'],
            'question_text' => ['required', 'string'],
            'question_type' => ['required', 'in:text,textarea,radio,select,rating,yes_no'],
            'options_text' => ['nullable', 'string'],
            'is_required' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $options = null;
        if (!empty($data['options_text']) && in_array($data['question_type'], ['radio', 'select'])) {
            $lines = preg_split('/\r\n|\r|\n/', trim($data['options_text']));
            $options = array_values(array_filter(array_map('trim', $lines)));
        }

        EvaluationQuestion::create([
            'evaluation_form_id' => $evaluationForm->id,
            'section_name' => $data['section_name'] ?? null,
            'question_text' => $data['question_text'],
            'question_type' => $data['question_type'],
            'options_json' => $options,
            'is_required' => (bool) ($data['is_required'] ?? false),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
        ]);

        return redirect()
            ->route('admin.evaluation-forms.questions.index', $evaluationForm)
            ->with('success', 'Evaluation question added successfully.');
    }

    public function edit(EvaluationForm $evaluationForm, EvaluationQuestion $question): View
    {
        abort_unless($question->evaluation_form_id === $evaluationForm->id, 404);

        return view('admin.evaluation_questions.edit', compact('evaluationForm', 'question'));
    }

    public function update(Request $request, EvaluationForm $evaluationForm, EvaluationQuestion $question): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole('super-admin'), 403);
        abort_unless($question->evaluation_form_id === $evaluationForm->id, 404);

        $data = $request->validate([
            'section_name' => ['nullable', 'string', 'max:150'],
            'question_text' => ['required', 'string'],
            'question_type' => ['required', 'in:text,textarea,radio,select,rating,yes_no'],
            'options_text' => ['nullable', 'string'],
            'is_required' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $options = null;
        if (!empty($data['options_text']) && in_array($data['question_type'], ['radio', 'select'])) {
            $lines = preg_split('/\r\n|\r|\n/', trim($data['options_text']));
            $options = array_values(array_filter(array_map('trim', $lines)));
        }

        $question->update([
            'section_name' => $data['section_name'] ?? null,
            'question_text' => $data['question_text'],
            'question_type' => $data['question_type'],
            'options_json' => $options,
            'is_required' => (bool) ($data['is_required'] ?? false),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
        ]);

        return redirect()
            ->route('admin.evaluation-forms.questions.index', $evaluationForm)
            ->with('success', 'Evaluation question updated successfully.');
    }

    public function destroy(EvaluationForm $evaluationForm, EvaluationQuestion $question): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole('super-admin'), 403);
        abort_unless($question->evaluation_form_id === $evaluationForm->id, 404);

        $question->delete();

        return redirect()
            ->route('admin.evaluation-forms.questions.index', $evaluationForm)
            ->with('success', 'Evaluation question deleted successfully.');
    }
}
