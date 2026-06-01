@extends('layouts.admin')

@section('title', 'Users')

@section('content')
<style>
    .users-page {
        padding: 1.5rem;
    }

    .users-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .users-title {
        font-size: 2rem;
        font-weight: 900;
        color: #102033;
        margin-bottom: .35rem;
    }

    .users-subtitle {
        color: #64748b;
        font-size: 1rem;
        margin-bottom: 0;
    }

    .users-top-actions {
        display: flex;
        gap: .75rem;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .btn-soft {
        border-radius: .9rem;
        padding: .72rem 1rem;
        font-weight: 850;
        border: 1px solid #dbe7e2;
        background: #fff;
        color: #0f172a;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
    }

    .btn-soft:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    .btn-green {
        border-radius: .9rem;
        padding: .72rem 1rem;
        font-weight: 850;
        border: 1px solid #0b6b3a;
        background: #0b6b3a;
        color: #fff;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        box-shadow: 0 10px 20px rgba(11,107,58,.15);
    }

    .btn-green:hover {
        background: #095c32;
        color: #fff;
    }

    .users-summary {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .summary-box {
        background: #ffffff;
        border: 1px solid #dbe7e2;
        border-radius: 1.15rem;
        padding: 1.1rem;
        box-shadow: 0 10px 25px rgba(15,23,42,.05);
    }

    .summary-box small {
        display: block;
        color: #64748b;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .05em;
        font-size: .75rem;
        margin-bottom: .3rem;
    }

    .summary-box strong {
        display: block;
        color: #0f172a;
        font-size: 1.45rem;
        font-weight: 900;
    }

    .users-card {
        background: #ffffff;
        border: 1px solid #dbe7e2;
        border-radius: 1.35rem;
        box-shadow: 0 12px 34px rgba(15, 23, 42, .07);
        overflow: hidden;
    }

    .users-card-header {
        padding: 1.15rem 1.35rem;
        background: #fbfdfc;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .users-card-title {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 900;
        color: #102033;
    }

    .users-search {
        display: flex;
        gap: .6rem;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .users-search input,
    .users-search select {
        border: 1px solid #cfe1dc;
        border-radius: .85rem;
        padding: .65rem .85rem;
        outline: none;
        min-width: 200px;
    }

    .users-search input:focus,
    .users-search select:focus {
        border-color: #0b6b3a;
        box-shadow: 0 0 0 .2rem rgba(11, 107, 58, .12);
    }

    .users-table {
        margin-bottom: 0;
    }

    .users-table thead th {
        background: #f8fbfa;
        color: #0f172a;
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .05em;
        font-weight: 900;
        border-bottom: 1px solid #e5e7eb;
        padding: 1rem;
        white-space: nowrap;
    }

    .users-table tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #edf2f0;
    }

    .user-identity {
        display: flex;
        align-items: center;
        gap: .85rem;
        min-width: 240px;
    }

    .user-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0b6b3a 0%, #0f8a4d 100%);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        flex-shrink: 0;
    }

    .user-name {
        font-weight: 900;
        color: #0f172a;
        line-height: 1.25;
    }

    .user-email {
        color: #64748b;
        font-size: .85rem;
        font-weight: 650;
        word-break: break-word;
    }

    .role-badges {
        display: flex;
        gap: .4rem;
        flex-wrap: wrap;
        max-width: 360px;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: .42rem .65rem;
        font-size: .75rem;
        font-weight: 900;
        text-transform: capitalize;
        border: 1px solid transparent;
        white-space: nowrap;
    }

    .role-super-admin {
        background: #fff1f2;
        color: #be123c;
        border-color: #fecdd3;
    }

    .role-programme-coordinator {
        background: #eff6ff;
        color: #1d4ed8;
        border-color: #bfdbfe;
    }

    .role-attendance-officer {
        background: #ecfdf5;
        color: #047857;
        border-color: #bbf7d0;
    }

    .role-me-officer,
    .role-m-e-officer {
        background: #f5f3ff;
        color: #6d28d9;
        border-color: #ddd6fe;
    }

    .role-participant {
        background: #f8fafc;
        color: #475569;
        border-color: #e2e8f0;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        border-radius: 999px;
        padding: .45rem .7rem;
        font-size: .78rem;
        font-weight: 900;
        white-space: nowrap;
    }

    .status-active {
        background: rgba(11,107,58,.1);
        color: #0b6b3a;
    }

    .status-inactive {
        background: #f1f5f9;
        color: #475569;
    }

    .status-locked {
        background: #fff1f2;
        color: #be123c;
    }

    .password-pill {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: .42rem .65rem;
        font-size: .75rem;
        font-weight: 850;
        background: #fff7ed;
        color: #c2410c;
        border: 1px solid #fed7aa;
        white-space: nowrap;
    }

    .password-pill.ok {
        background: #f8fafc;
        color: #64748b;
        border-color: #e2e8f0;
    }

    .user-actions {
        display: flex;
        justify-content: flex-end;
        gap: .45rem;
        flex-wrap: wrap;
        min-width: 220px;
    }

    .action-btn {
        border-radius: .75rem;
        padding: .52rem .7rem;
        font-size: .8rem;
        font-weight: 850;
        text-decoration: none;
        border: 1px solid transparent;
        display: inline-flex;
        align-items: center;
        gap: .32rem;
        line-height: 1;
        background: #fff;
        cursor: pointer;
    }

    .action-view {
        border-color: #cbd5e1;
        color: #0f172a;
    }

    .action-view:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    .action-edit {
        border-color: #bbf7d0;
        background: #ecfdf5;
        color: #047857;
    }

    .action-edit:hover {
        background: #dcfce7;
        color: #047857;
    }

    .action-lock {
        border-color: #fed7aa;
        background: #fff7ed;
        color: #c2410c;
    }

    .action-lock:hover {
        background: #ffedd5;
        color: #c2410c;
    }

    .action-danger {
        border-color: #fecdd3;
        background: #fff1f2;
        color: #be123c;
    }

    .action-danger:hover {
        background: #ffe4e6;
        color: #be123c;
    }

    .empty-state {
        padding: 4rem 1.5rem;
        text-align: center;
    }

    .empty-icon {
        width: 70px;
        height: 70px;
        border-radius: 1.25rem;
        background: rgba(11,107,58,.1);
        color: #0b6b3a;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        font-weight: 900;
        color: #0f172a;
        margin-bottom: .35rem;
    }

    .empty-state p {
        color: #64748b;
        margin-bottom: 0;
    }

    @media (max-width: 1100px) {
        .users-summary {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .users-page {
            padding: 1rem;
        }

        .users-header {
            flex-direction: column;
        }

        .users-top-actions,
        .users-search {
            width: 100%;
            justify-content: stretch;
        }

        .users-top-actions a,
        .users-search input,
        .users-search select,
        .users-search button {
            width: 100%;
        }

        .users-summary {
            grid-template-columns: 1fr;
        }

        .user-actions {
            justify-content: flex-start;
        }
    }

    .pagination {
        display: flex;
        gap: .35rem;
        flex-wrap: wrap;
        align-items: center;
        margin: 0;
    }

    .pagination .page-item {
        list-style: none;
    }

    .pagination .page-link {
        border: 1px solid #dbe7e2;
        color: #0f172a;
        border-radius: .65rem;
        padding: .45rem .7rem;
        font-weight: 800;
        background: #fff;
        min-width: 38px;
        text-align: center;
        line-height: 1.2;
    }

    .pagination .page-link:hover {
        background: #f8fafc;
        color: #0b6b3a;
        border-color: #0b6b3a;
    }

    .pagination .active .page-link {
        background: #0b6b3a;
        border-color: #0b6b3a;
        color: #fff;
    }

    .pagination .disabled .page-link {
        color: #94a3b8;
        background: #f8fafc;
    }

    .pagination svg {
        width: 16px !important;
        height: 16px !important;
    }


    .users-pagination-wrap {
        border-top: 1px solid #edf2f0;
        padding: 1rem 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
        background: #fbfdfc;
    }

    .pagination-summary {
        color: #64748b;
        font-size: .9rem;
        font-weight: 700;
    }

    .pagination-buttons {
        display: flex;
        align-items: center;
        gap: .4rem;
        flex-wrap: wrap;
    }

    .page-btn,
    .page-number,
    .page-dots {
        min-width: 38px;
        height: 38px;
        padding: 0 .7rem;
        border-radius: .75rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: .88rem;
        font-weight: 850;
        text-decoration: none;
        line-height: 1;
    }

    .page-btn,
    .page-number {
        border: 1px solid #dbe7e2;
        background: #ffffff;
        color: #0f172a;
    }

    .page-btn:hover,
    .page-number:hover {
        border-color: #0b6b3a;
        background: #f0fdf8;
        color: #0b6b3a;
    }

    .page-number.active {
        background: #0b6b3a;
        border-color: #0b6b3a;
        color: #ffffff;
        box-shadow: 0 8px 18px rgba(11,107,58,.16);
    }

    .page-btn.disabled {
        background: #f8fafc;
        color: #94a3b8;
        cursor: not-allowed;
    }

    .page-dots {
        color: #94a3b8;
        min-width: 28px;
    }

    @media (max-width: 768px) {
        .users-pagination-wrap {
            align-items: stretch;
        }

        .pagination-buttons {
            width: 100%;
        }

        .page-btn {
            flex: 1;
        }
    }

</style>

@php
    $userCollection = collect();

    if (isset($users)) {
        if ($users instanceof \Illuminate\Pagination\AbstractPaginator) {
            $userCollection = collect($users->items());
        } else {
            $userCollection = collect($users);
        }
    }

    $totalUsers = method_exists($users ?? null, 'total') ? $users->total() : $userCollection->count();
    $activeUsers = $userCollection->filter(fn ($user) => ($user->status ?? 'active') === 'active')->count();
    $lockedUsers = $userCollection->filter(fn ($user) => ($user->status ?? '') === 'locked')->count();
    $forcePasswordUsers = $userCollection->filter(fn ($user) => (bool)($user->must_change_password ?? false))->count();

    $roleClass = function ($roleName) {
        $clean = str_replace(['&', ' '], ['-', '-'], strtolower($roleName));
        $clean = str_replace(['--', '_'], ['-', '-'], $clean);

        if ($clean === 'm-e-officer') {
            return 'role-m-e-officer';
        }

        return 'role-' . $clean;
    };

    $initials = function ($name) {
        return collect(explode(' ', trim((string) $name)))
            ->filter()
            ->take(2)
            ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
            ->join('') ?: 'U';
    };
@endphp

<div class="users-page">

    <div class="users-header">
        <div>
            <h1 class="users-title">Users</h1>
            <p class="users-subtitle">Manage system accounts, roles, status, and access control.</p>
        </div>

        <div class="users-top-actions">
            @if(Route::has('admin.users.grouped'))
                <a href="{{ route('admin.users.grouped') }}" class="btn-soft">
                    Grouped View
                </a>
            @endif

            @if(Route::has('admin.users.create'))
                <a href="{{ route('admin.users.create') }}" class="btn-green">
                    + Create User
                </a>
            @endif

            @if(Route::has('admin.users.bulk-create-participant-users'))
                <form method="POST" action="{{ route('admin.users.bulk-create-participant-users') }}">
                    @csrf
                    <button type="submit" class="btn-soft">
                        Create Participant Users
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success rounded-4">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger rounded-4">{{ session('error') }}</div>
    @endif

    <div class="users-summary">
        <div class="summary-box">
            <small>Total Users</small>
            <strong>{{ number_format($totalUsers) }}</strong>
        </div>

        <div class="summary-box">
            <small>Active on this page</small>
            <strong>{{ number_format($activeUsers) }}</strong>
        </div>

        <div class="summary-box">
            <small>Locked on this page</small>
            <strong>{{ number_format($lockedUsers) }}</strong>
        </div>

        <div class="summary-box">
            <small>Must Change Password</small>
            <strong>{{ number_format($forcePasswordUsers) }}</strong>
        </div>
    </div>

    <div class="users-card">
        <div class="users-card-header">
            <h2 class="users-card-title">User Accounts</h2>

            <form method="GET" action="{{ route('admin.users.index') }}" class="users-search">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search name or email"
                >

                <select name="role">
                    <option value="">All roles</option>
                    @isset($roles)
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" @selected(request('role') === $role->name)>
                                {{ str_replace('-', ' ', ucfirst($role->name)) }}
                            </option>
                        @endforeach
                    @endisset
                </select>

                <button type="submit" class="btn-soft">Filter</button>

                @if(request()->hasAny(['search', 'role']))
                    <a href="{{ route('admin.users.index') }}" class="btn-soft">Reset</a>
                @endif
            </form>
        </div>

        @if ($userCollection->count())
            <div class="table-responsive">
                <table class="table users-table align-middle">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Roles</th>
                            <th>Status</th>
                            <th>Password</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($userCollection as $user)
                            @php
                                $status = strtolower((string)($user->status ?? 'active'));
                                $statusClass = match($status) {
                                    'locked' => 'status-locked',
                                    'inactive' => 'status-inactive',
                                    default => 'status-active',
                                };
                            @endphp

                            <tr>
                                <td>
                                    <div class="user-identity">
                                        <div class="user-avatar">{{ $initials($user->name ?? $user->email) }}</div>
                                        <div>
                                            <div class="user-name">{{ $user->name ?? '—' }}</div>
                                            <div class="user-email">{{ $user->email ?? '—' }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="role-badges">
                                        @forelse($user->roles ?? [] as $role)
                                            <span class="role-badge {{ $roleClass($role->name) }}">
                                                {{ str_replace('-', ' ', $role->name) }}
                                            </span>
                                        @empty
                                            <span class="role-badge role-participant">No role</span>
                                        @endforelse
                                    </div>
                                </td>

                                <td>
                                    <span class="status-pill {{ $statusClass }}">
                                        ● {{ ucfirst($status ?: 'active') }}
                                    </span>
                                </td>

                                <td>
                                    @if((bool)($user->must_change_password ?? false))
                                        <span class="password-pill">Required</span>
                                    @else
                                        <span class="password-pill ok">Not required</span>
                                    @endif
                                </td>

                                <td class="text-end">
                                    <div class="user-actions">
                                        @if(Route::has('admin.users.show'))
                                            <a href="{{ route('admin.users.show', $user) }}" class="action-btn action-view">
                                                View
                                            </a>
                                        @endif

                                        @if(Route::has('admin.users.edit'))
                                            <a href="{{ route('admin.users.edit', $user) }}" class="action-btn action-edit">
                                                Edit
                                            </a>
                                        @endif

                                        @if(Route::has('admin.users.lock'))
                                            <form method="POST" action="{{ route('admin.users.lock', $user) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="action-btn action-lock">
                                                    Lock
                                                </button>
                                            </form>
                                        @endif

                                        @if(Route::has('admin.users.toggle-status'))
                                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="action-btn action-view">
                                                    Toggle
                                                </button>
                                            </form>
                                        @endif

                                        @if(Route::has('admin.users.destroy'))
                                            <form
                                                method="POST"
                                                action="{{ route('admin.users.destroy', $user) }}"
                                                class="d-inline"
                                                
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button data-confirm-delete data-delete-title="Delete User" data-delete-message="Are you sure you want to delete this user account? This action cannot be undone." type="submit" class="action-btn action-danger">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(method_exists($users ?? null, 'links') && $users->hasPages())
                <div class="users-pagination-wrap">
                    <div class="pagination-summary">
                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                    </div>

                    <div class="pagination-buttons">
                        @if ($users->onFirstPage())
                            <span class="page-btn disabled">‹ Previous</span>
                        @else
                            <a href="{{ $users->previousPageUrl() }}" class="page-btn">‹ Previous</a>
                        @endif

                        @php
                            $current = $users->currentPage();
                            $last = $users->lastPage();
                            $start = max($current - 2, 1);
                            $end = min($current + 2, $last);
                        @endphp

                        @if ($start > 1)
                            <a href="{{ $users->url(1) }}" class="page-number">1</a>
                            @if ($start > 2)
                                <span class="page-dots">…</span>
                            @endif
                        @endif

                        @for ($page = $start; $page <= $end; $page++)
                            @if ($page == $current)
                                <span class="page-number active">{{ $page }}</span>
                            @else
                                <a href="{{ $users->url($page) }}" class="page-number">{{ $page }}</a>
                            @endif
                        @endfor

                        @if ($end < $last)
                            @if ($end < $last - 1)
                                <span class="page-dots">…</span>
                            @endif
                            <a href="{{ $users->url($last) }}" class="page-number">{{ $last }}</a>
                        @endif

                        @if ($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}" class="page-btn">Next ›</a>
                        @else
                            <span class="page-btn disabled">Next ›</span>
                        @endif
                    </div>
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-icon">👥</div>
                <h5>No users found</h5>
                <p>Create a user account or adjust your filters.</p>
            </div>
        @endif
    </div>
</div>
@endsection
