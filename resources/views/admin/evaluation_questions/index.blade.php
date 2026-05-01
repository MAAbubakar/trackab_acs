@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <h3 class="page-title">Evaluation Questions</h3>
        <div class="page-subtitle">{{ $evaluationForm->title }}</div>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.evaluation-forms.index') }}" class="btn btn-secondary">Back</a>
        @role('super-admin')
        <a href="{{ route('admin.evaluation-forms.questions.create', $evaluationForm) }}" class="btn btn-primary">Add Question</a>
        @endrole
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Section</th>
                    <th>Question</th>
                    <th>Type</th>
                    <th>Required</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($evaluationForm->questions as $question)
                    <tr>
                        <td>{{ $question->sort_order }}</td>
                        <td>{{ $question->section_name ?? '-' }}</td>
                        <td>{{ $question->question_text }}</td>
                        <td>{{ $question->question_type }}</td>
                        <td>{{ $question->is_required ? 'Yes' : 'No' }}</td>
                        <td>
                            @role('super-admin')
                            <a href="{{ route('admin.evaluation-forms.questions.edit', [$evaluationForm, $question]) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('admin.evaluation-forms.questions.destroy', [$evaluationForm, $question]) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this question?')">Delete</button>
                            </form>
                            @endrole
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">No questions added yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
