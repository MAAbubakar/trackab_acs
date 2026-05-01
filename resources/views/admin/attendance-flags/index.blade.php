@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Attendance Flags</h3>
            <div class="page-subtitle">Track attendance anomalies, review issues, and resolve them.</div>
        </div>
    </div>

    <div class="card" style="margin-bottom:20px;">
        <div class="card-body">
            <form action="{{ route('admin.attendance-flags.store') }}" method="POST" class="form-grid content-narrow">
                @csrf

                <div class="two-col-grid">
                    <div>
                        <label for="participant_id">Participant</label>
                        <select name="participant_id" id="participant_id" required>
                            <option value="">Select participant</option>
                            @foreach($participants as $participant)
                                <option value="{{ $participant->id }}">
                                    {{ $participant->full_name }} ({{ $participant->participant_no }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="training_session_id">Session</label>
                        <select name="training_session_id" id="training_session_id" required>
                            <option value="">Select session</option>
                            @foreach($sessions as $session)
                                <option value="{{ $session->id }}">
                                    {{ $session->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="two-col-grid">
                    <div>
                        <label for="flag_type">Flag Type</label>
                        <input type="text" name="flag_type" id="flag_type" placeholder="e.g. duplicate_scan, wrong_batch, device_mismatch" required>
                    </div>

                    <div>
                        <label for="status">Status</label>
                        <select name="status" id="status" required>
                            <option value="open">Open</option>
                            <option value="resolved">Resolved</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="reason">Reason</label>
                    <textarea name="reason" id="reason" rows="4" required></textarea>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary">Create Flag</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-wrap">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Participant</th>
                            <th>Session</th>
                            <th>Flag Type</th>
                            <th>Status</th>
                            <th>Reason</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($flags as $flag)
                            <tr>
                                <td>{{ $flag->participant?->full_name ?? 'N/A' }}</td>
                                <td>{{ $flag->session?->title ?? 'N/A' }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $flag->flag_type ?? 'n/a')) }}</td>
                                <td>
                                    <span class="badge {{ ($flag->status ?? '') === 'resolved' ? 'badge-success' : 'badge-warning' }}">
                                        {{ ucfirst($flag->status ?? 'open') }}
                                    </span>
                                </td>
                                <td>{{ $flag->reason ?? 'N/A' }}</td>
                                <td>
                                    @if(($flag->status ?? '') !== 'resolved')
                                        <form action="{{ route('admin.attendance-flags.resolve', $flag) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success">Resolve</button>
                                        </form>
                                    @else
                                        <span class="badge badge-success">Resolved</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">No attendance flags found.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($flags, 'links'))
                <div class="mt-4">
                    {{ $flags->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
