@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Users</h3>
            <div class="page-subtitle">Manage admin and participant user accounts.</div>
        </div>

        <div class="actions-inline">
            <form action="{{ route('admin.users.bulk-create-participant-users') }}" method="POST" class="inline-form">
                @csrf
                <input type="hidden" name="limit" value="50">
                <button type="submit" class="btn btn-secondary" onclick="return confirm('Create user accounts for up to 50 unlinked participants?')">
                    Bulk Create Participant Users
                </button>
            </form>

            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Add User</a>
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}" class="filter-row">
                <div>
                    <label for="search">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search name or email">
                </div>

                <div>
                    <label for="role">Role</label>
                    <select name="role" id="role">
                        <option value="">All roles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status">Status</label>
                    <select name="status" id="status">
                        <option value="">All statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="locked" {{ request('status') === 'locked' ? 'selected' : '' }}>Locked</option>
                    </select>
                </div>

                <div class="actions-inline">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <form action="{{ route('admin.users.bulk-status') }}" method="POST">
        @csrf

        <div class="card mb-5">
            <div class="card-body">
                <div class="actions-inline">
                    <select name="status" required class="input-auto">
                        <option value="">Bulk status...</option>
                        <option value="active">Set Active</option>
                        <option value="inactive">Set Inactive</option>
                        <option value="locked">Set Locked</option>
                    </select>

                    <button type="submit" class="btn btn-warning" onclick="return confirm('Apply this status to selected users?')">
                        Apply to Selected
                    </button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-wrap">
                    <table class="app-table">
                        <thead>
                            <tr>
                                <th width="40">
                                    <input type="checkbox" id="select-all-users" class="input-auto">
                                </th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Status</th>
                                <th>Password Reset</th>
                                <th>Participant Linked</th>
                                <th width="420">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>
                                        @if((int) auth()->id() !== (int) $user->id)
                                            <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="user-select input-auto">
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $user->name }}</strong>
                                        @if((int) auth()->id() === (int) $user->id)
                                            <div class="metric-note">Current user</div>
                                        @endif
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->roles->pluck('name')->join(', ') ?: '—' }}</td>
                                    <td>
                                        <span class="badge {{
                                            $user->status === 'active' ? 'badge-success' :
                                            ($user->status === 'locked' ? 'badge-danger' : 'badge-warning')
                                        }}">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $user->must_change_password ? 'badge-warning' : 'badge-neutral' }}">
                                            {{ $user->must_change_password ? 'Required' : 'No' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($user->participant)
                                            <span class="badge badge-success">Yes</span>
                                            <div class="metric-note">{{ $user->participant->participant_no }}</div>
                                        @else
                                            <span class="badge badge-neutral">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="actions-inline">
                                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">View</a>
                                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">Edit</a>

                                            <form action="{{ route('admin.users.send-invitation', $user) }}" method="POST" class="inline-form">
                                                @csrf
                                                <button type="submit" class="btn btn-secondary">Invite</button>
                                            </form>

                                            <form action="{{ route('admin.users.resend-reset', $user) }}" method="POST" class="inline-form">
                                                @csrf
                                                <button type="submit" class="btn btn-warning">Reset</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="empty-state">No users found.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAll = document.getElementById('select-all-users');
            const boxes = document.querySelectorAll('.user-select');

            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    boxes.forEach(box => {
                        box.checked = selectAll.checked;
                    });
                });
            }
        });
    </script>
@endsection
