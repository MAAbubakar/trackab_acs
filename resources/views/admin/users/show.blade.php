@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">User Details</h3>
            <div class="page-subtitle">{{ $user->name }}</div>
        </div>

        <div class="actions-inline">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back</a>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">Edit</a>
        </div>
    </div>

    <div class="section-stack">
        <div class="card">
            <div class="card-body">
                <h4 class="mt-0">Account Information</h4>

                <div class="two-col-grid">
                    <div>
                        <div><strong>Name:</strong> {{ $user->name }}</div>
                        <div><strong>Email:</strong> {{ $user->email }}</div>
                        <div><strong>Status:</strong> {{ ucfirst($user->status) }}</div>
                    </div>

                    <div>
                        <div><strong>Roles:</strong> {{ $user->roles->pluck('name')->join(', ') ?: '—' }}</div>
                        <div><strong>Password Reset Required:</strong> {{ $user->must_change_password ? 'Yes' : 'No' }}</div>
                        <div><strong>Created:</strong> {{ $user->created_at?->format('d M Y h:i A') ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="pt-2 actions-inline">
                    <form action="{{ route('admin.users.send-invitation', $user) }}" method="POST" class="inline-form">
                        @csrf
                        <button type="submit" class="btn btn-secondary">Record Invitation</button>
                    </form>

                    <form action="{{ route('admin.users.resend-reset', $user) }}" method="POST" class="inline-form">
                        @csrf
                        <button type="submit" class="btn btn-warning">Reissue Reset Requirement</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h4 class="mt-0">Participant Linkage</h4>

                @if($user->participant)
                    <div class="two-col-grid">
                        <div>
                            <div><strong>Participant ID:</strong> {{ $user->participant->id }}</div>
                            <div><strong>Participant No:</strong> {{ $user->participant->participant_no ?? 'N/A' }}</div>
                            <div><strong>Full Name:</strong> {{ $user->participant->full_name ?? 'N/A' }}</div>
                        </div>

                        <div>
                            <div><strong>Course:</strong> {{ $user->participant->course?->title ?? 'N/A' }}</div>
                            <div><strong>Batch:</strong> {{ $user->participant->batch?->name ?? 'N/A' }}</div>
                            <div><strong>Status:</strong> {{ ucfirst($user->participant->status ?? 'inactive') }}</div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <form action="{{ route('admin.users.unlink-participant', $user) }}" method="POST" class="inline-form">
                            @csrf
                            <button type="submit" class="btn btn-warning" onclick="return confirm('Unlink this participant from the user?')">
                                Unlink Participant
                            </button>
                        </form>
                    </div>
                @else
                    <div class="empty-state">This user is not linked to any participant record.</div>

                    @if($availableParticipants->count())
                        <form action="{{ route('admin.users.link-participant', $user) }}" method="POST" class="form-grid content-narrow">
                            @csrf

                            <div>
                                <label for="participant_id">Link Participant</label>
                                <select name="participant_id" id="participant_id" required>
                                    <option value="">Select participant</option>
                                    @foreach($availableParticipants as $participant)
                                        <option value="{{ $participant->id }}">
                                            {{ $participant->full_name }} ({{ $participant->participant_no ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="btn btn-primary">Link Participant</button>
                            </div>
                        </form>
                    @else
                        <div class="metric-note mt-3">No unlinked participants are currently available.</div>
                    @endif
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h4 class="mt-0">User Audit History</h4>

                <div class="table-wrap">
                    <table class="app-table">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Action</th>
                                <th>Actor</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->audits()->latest()->limit(20)->get() as $audit)
                                <tr>
                                    <td>{{ $audit->created_at?->format('d M Y h:i A') ?? 'N/A' }}</td>
                                    <td>{{ $audit->action }}</td>
                                    <td>{{ $audit->actor?->name ?? 'System/Unknown' }}</td>
                                    <td>{{ $audit->notes ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">No audit history recorded yet.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
