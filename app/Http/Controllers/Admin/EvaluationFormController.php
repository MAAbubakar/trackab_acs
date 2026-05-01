<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\EvaluationForm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EvaluationFormController extends Controller
{
    public function index(): View
    {
        $forms = EvaluationForm::with(['batch', 'creator'])
            ->latest()
            ->paginate(15);

        return view('admin.evaluation_forms.index', compact('forms'));
    }

    public function create(): View
    {
        $batches = Batch::orderByDesc('id')->get();

        return view('admin.evaluation_forms.create', compact('batches'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole('super-admin'), 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'track_scope' => ['required', 'in:Track A,Track B,Both'],
            'batch_id' => ['nullable', 'exists:batches,id'],
            'is_active' => ['nullable', 'boolean'],
            'opens_at' => ['nullable', 'date'],
            'closes_at' => ['nullable', 'date', 'after_or_equal:opens_at'],
        ]);

        $data['is_active'] = (bool) ($data['is_active'] ?? true);
        $data['created_by'] = auth()->id();

        $form = EvaluationForm::create($data);

        return redirect()
            ->route('admin.evaluation-forms.edit', $form)
            ->with('success', 'Evaluation form created successfully.');
    }

    public function show(EvaluationForm $evaluationForm): View
    {
        $evaluationForm->load(['batch', 'creator', 'questions']);

        return view('admin.evaluation_forms.show', compact('evaluationForm'));
    }

    public function edit(EvaluationForm $evaluationForm): View
    {
        $evaluationForm->load('questions');
        $batches = Batch::orderByDesc('id')->get();

        return view('admin.evaluation_forms.edit', compact('evaluationForm', 'batches'));
    }

    public function update(Request $request, EvaluationForm $evaluationForm): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole('super-admin'), 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'track_scope' => ['required', 'in:Track A,Track B,Both'],
            'batch_id' => ['nullable', 'exists:batches,id'],
            'is_active' => ['nullable', 'boolean'],
            'opens_at' => ['nullable', 'date'],
            'closes_at' => ['nullable', 'date', 'after_or_equal:opens_at'],
        ]);

        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        $evaluationForm->update($data);

        return redirect()
            ->route('admin.evaluation-forms.index')
            ->with('success', 'Evaluation form updated successfully.');
    }

    public function destroy(EvaluationForm $evaluationForm): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole('super-admin'), 403);

        $evaluationForm->delete();

        return redirect()
            ->route('admin.evaluation-forms.index')
            ->with('success', 'Evaluation form deleted successfully.');
    }
}
