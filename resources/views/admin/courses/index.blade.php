@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Courses</h3>
            <div class="page-subtitle">Manage training courses and programme offerings.</div>
        </div>

        <div>
            <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">Add Course</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-wrap">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th width="240">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                            <tr>
                                <td>{{ $course->title }}</td>
                                <td>{{ $course->code ?? 'N/A' }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($course->description, 80) ?: 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ $course->status === 'active' ? 'badge-success' : 'badge-warning' }}">
                                        {{ ucfirst($course->status ?? 'inactive') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="actions-inline">
                                        <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-secondary">View</a>
                                        <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-primary">Edit</a>

                                        <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button data-confirm-delete data-delete-title="Delete Course" data-delete-message="Are you sure you want to delete this course? This action cannot be undone." type="submit" class="btn btn-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">No courses found yet.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $courses->links() }}
            </div>
        </div>
    </div>
@endsection
