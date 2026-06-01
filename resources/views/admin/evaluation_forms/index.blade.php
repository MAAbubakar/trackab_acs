@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <h3 class="page-title">Evaluation Forms</h3>
        <div class="page-subtitle">Manage registration-linked evaluation forms.</div>
    </div>
    @role('super-admin')
    <a href="{{ route('admin.evaluation-forms.create') }}" class="btn btn-primary">Add Evaluation Form</a>
    @endrole
</div>

<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Track Scope</th>
                    <th>Batch</th>
                    <th>Status</th>
                    <th>Window</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($forms as $form)
                    <tr>
                        <td>{{ $form->title }}</td>
                        <td>{{ $form->track_scope }}</td>
                        <td>{{ $form->batch?->name ?? 'General' }}</td>
                        <td>{{ $form->is_active ? 'Active' : 'Inactive' }}</td>
                        <td>
                            {{ $form->opens_at?->format('d M Y H:i') ?? '-' }}
                            -
                            {{ $form->closes_at?->format('d M Y H:i') ?? '-' }}
                        </td>
                        <td>
                            <a href="{{ route('admin.evaluation-forms.show', $form) }}" class="btn btn-secondary">View</a>
                            <a href="{{ route('admin.evaluation-forms.questions.index', $form) }}" class="btn btn-secondary">Questions</a>
                            @role('super-admin')
                            <a href="{{ route('admin.evaluation-forms.edit', $form) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('admin.evaluation-forms.destroy', $form) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="btn btn-danger"
                                    data-confirm-delete
                                    data-delete-title="Delete Evaluation Form"
                                    data-delete-message="Are you sure you want to delete this evaluation form? This action cannot be undone."
                                >
                                    Delete
                                </button>
                            </form>
                            @endrole
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">No evaluation forms found.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $forms->links() }}
    </div>
</div>
@endsection
