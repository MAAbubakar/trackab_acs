@extends('layouts.admin')

@section('title', 'Grouped Users')

@section('content')
<style>
    .grouped-users-page {
        padding: 1.5rem;
    }

    .grouped-users-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .grouped-users-title {
        font-size: 2rem;
        font-weight: 950;
        color: #102033;
        margin: 0 0 .35rem;
    }

    .grouped-users-subtitle {
        color: #64748b;
        font-size: 1rem;
        margin: 0;
        font-weight: 650;
    }

    .grouped-actions {
        display: flex;
        gap: .75rem;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .btn-soft,
    .btn-green {
        border-radius: .9rem;
        padding: .72rem 1rem;
        font-weight: 850;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        border: 1px solid #dbe7e2;
        cursor: pointer;
    }

    .btn-soft {
        background: #fff;
        color: #0f172a;
    }

    .btn-green {
        background: #0b6b3a;
        border-color: #0b6b3a;
        color: #fff;
        box-shadow: 0 10px 20px rgba(11,107,58,.15);
    }

    .grouped-summary {
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
        font-weight: 850;
        text-transform: uppercase;
        letter-spacing: .05em;
        font-size: .75rem;
        margin-bottom: .3rem;
    }

    .summary-box strong {
        display: block;
        color: #0f172a;
        font-size: 1.45rem;
        font-weight: 950;
    }

    .grouped-card {
        background: #ffffff;
        border: 1px solid #dbe7e2;
        border-radius: 1.35rem;
        box-shadow: 0 12px 34px rgba(15, 23, 42, .07);
        overflow: hidden;
    }

    .grouped-card-header {
        padding: 1.15rem 1.35rem;
        background: #fbfdfc;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .grouped-card-title {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 950;
        color: #102033;
    }

    .grouped-search {
        display: flex;
        gap: .6rem;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .grouped-search input,
    .grouped-search select {
        border: 1px solid #cfe1dc;
        border-radius: .85rem;
        padding: .65rem .85rem;
        outline: none;
        min-width: 200px;
    }

    .role-groups {
        padding: 1.2rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .role-group {
        border: 1px solid #dbe7e2;
        border-radius: 1.15rem;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 10px 28px rgba(15, 23, 42, .05);
    }

    .role-group summary {
        list-style: none;
        cursor: pointer;
        padding: 1rem 1.15rem;
        background: linear-gradient(135deg, #f8fafc, #ecfdf5);
        display: grid;
        grid-template-columns: 1.25fr .7fr .7fr .8fr auto;
        gap: 1rem;
        align-items: center;
    }

    .role-group summary::-webkit-details-marker {
        display: none;
    }

    .role-title {
        font-size: 1.05rem;
        font-weight: 950;
        color: #0f172a;
        text-transform: capitalize;
    }

    .role-subtitle {
        margin-top: .25rem;
        color: #64748b;
        font-size: .82rem;
        font-weight: 750;
    }

    .role-stat {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: fit-content;
        padding: .45rem .7rem;
        border-radius: 999px;
        background: #ffffff;
        border: 1px solid #dbe7e2;
        color: #334155;
        font-size: .78rem;
        font-weight: 900;
        white-space: nowrap;
    }

    .role-stat.active {
        background: rgba(11,107,58,.1);
        color: #0b6b3a;
        border-color: rgba(11,107,58,.18);
    }

    .role-stat.locked {
        background: #fff1f2;
        color: #be123c;
        border-color: #fecdd3;
    }

    .role-stat.password {
        background: #fff7ed;
        color: #c2410c;
        border-color: #fed7aa;
    }

    .role-toggle {
        width: 38px;
        height: 38px;
        border-radius: 999px;
        background: #0b6b57;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 950;
        transition: transform .2s ease;
    }

    details[open] .role-toggle {
        transform: rotate(180deg);
    }

    .role-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .role-table {
        width: 100%;
        min-width: 980px;
        border-collapse: collapse;
        margin: 0;
    }

    .role-table th {
        background: #fbfdfc;
        color: #0f172a;
        font-size: .74rem;
        text-transform: uppercase;
        letter-spacing: .05em;
        font-weight: 950;
        padding: .9rem 1rem;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
        white-space: nowrap;
    }

    .role-table td {
        padding: 1rem;
        border-bottom: 1px solid #edf2f0;
        vertical-align: middle;
    }

    .role-table tr:hover td {
        background: #f8fafc;
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
        font-weight: 950;
        flex-shrink: 0;
    }

    .user-name {
        font-weight: 950;
        color: #0f172a;
        line-height: 1.25;
    }

    .user-email {
        color: #64748b;
        font-size: .85rem;
        font-weight: 650;
        word-break: break-word;
    }

    .role-badges,
    .user-actions {
        display: flex;
        gap: .4rem;
        flex-wrap: wrap;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: .42rem .65rem;
        font-size: .75rem;
        font-weight: 900;
        text-transform: capitalize;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #475569;
        white-space: nowrap;
    }

    .status-pill,
    .password-pill {
        display: inline-flex;
        align-items: center;
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
        background: #fff7ed;
        color: #c2410c;
        border: 1px solid #fed7aa;
    }

    .password-pill.ok {
        background: #f8fafc;
        color: #64748b;
        border-color: #e2e8f0;
    }

    .action-btn {
        border-radius: .75rem;
        padding: .52rem .7rem;
        font-size: .8rem;
        font-weight: 850;
        text-decoration: none;
        border: 1px solid transparent;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .action-view {
        background: #eef2ff;
        color: #3730a3;
        border-color: #c7d2fe;
    }

    .action-edit {
        background: #ecfdf5;
        color: #047857;
        border-color: #bbf7d0;
    }

    .action-lock {
        background: #fff7ed;
        color: #c2410c;
        border-color: #fed7aa;
    }

    .action-danger {
        background: #fff1f2;
        color: #be123c;
        border-color: #fecdd3;
    }

    .empty-state {
        padding: 2rem;
        text-align: center;
        color: #64748b;
        font-weight: 800;
    }

    @media (max-width: 900px) {
        .grouped-summary {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .role-group summary {
            grid-template-columns: 1fr;
        }

        .role-toggle {
            justify-self: start;
        }
    }

    @media (max-width: 620px) {
        .grouped-summary {
            grid-template-columns: 1fr;
        }

        .grouped-search {
            width: 100%;
        }

        .grouped-search input,
        .grouped-search select,
        .grouped-search button,
        .grouped-search a {
            width: 100%;
        }
    }
</style>

@php
    $userCollection = collect($users ?? []);

    $totalUsers = $userCollection->count();
    $activeUsers = $userCollection->filter(fn ($user) => strtolower((string)($user->status ?? 'active')) === 'active')->count();
    $lockedUsers = $userCollection->filter(fn ($user) => strtolower((string)($user->status ?? '')) === 'locked')->count();
    $forcePasswordUsers = $userCollection->filter(fn ($user) => (bool)($user->must_change_password ?? false))->count();

    $groupedUsers = $userCollection
        ->groupBy(function ($user) {
            return $user->roles->first()->name ?? 'no-role';
        })
        ->sortKeys();

    $initials = function ($name) {
        return collect(explode(' ', trim((string) $name)))
            ->filter()
            ->take(2)
            ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
            ->join('') ?: 'U';
    };

    $displayRole = function ($roleName) {
        return $roleName === 'no-role'
            ? 'No Role Assigned'
            : ucwords(str_replace('-', ' ', $roleName));
    };
@endphp

<div class="grouped-users-page">
    <div class="grouped-users-header">
        <div>
            <h1 class="grouped-users-title">Users by Role</h1>
            <p class="grouped-users-subtitle">A cleaner grouped view of system accounts, roles, status, and access control.</p>
        </div>

        <div class="grouped-actions">
            <a href="{{ route('admin.users.index') }}" class="btn-soft">Standard View</a>

            @if(Route::has('admin.users.create'))
                <a href="{{ route('admin.users.create') }}" class="btn-green">+ Create User</a>
            @endif
        </div>
    </div>

    <div class="grouped-summary">
        <div class="summary-box">
            <small>Total Users Found</small>
            <strong>{{ number_format($totalUsers) }}</strong>
        </div>

        <div class="summary-box">
            <small>Active Users</small>
            <strong>{{ number_format($activeUsers) }}</strong>
        </div>

        <div class="summary-box">
            <small>Locked Users</small>
            <strong>{{ number_format($lockedUsers) }}</strong>
        </div>

        <div class="summary-box">
            <small>Must Change Password</small>
            <strong>{{ number_format($forcePasswordUsers) }}</strong>
        </div>
    </div>

    <div class="grouped-card">
        <div class="grouped-card-header">
            <h2 class="grouped-card-title">Grouped User Accounts</h2>

            <form method="GET" action="{{ route('admin.users.grouped') }}" class="grouped-search">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search name or email"
                >

                <select name="role">
                    <option value="">All roles</option>
                    @foreach($roles ?? [] as $role)
                        <option value="{{ $role->name }}" @selected(request('role') === $role->name)>
                            {{ ucwords(str_replace('-', ' ', $role->name)) }}
                        </option>
                    @endforeach
                </select>

                <select name="status">
                    <option value="">All status</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                    <option value="locked" @selected(request('status') === 'locked')>Locked</option>
                </select>

                <button type="submit" class="btn-soft">Filter</button>

                @if(request()->hasAny(['search', 'role', 'status']))
                    <a href="{{ route('admin.users.grouped') }}" class="btn-soft">Reset</a>
                @endif
            </form>
        </div>

        <div class="role-groups">
            @forelse($groupedUsers as $roleName => $roleUsers)
                @php
                    $roleActive = $roleUsers->filter(fn ($user) => strtolower((string)($user->status ?? 'active')) === 'active')->count();
                    $roleLocked = $roleUsers->filter(fn ($user) => strtolower((string)($user->status ?? '')) === 'locked')->count();
                    $rolePassword = $roleUsers->filter(fn ($user) => (bool)($user->must_change_password ?? false))->count();
                @endphp

                <details class="role-group" @if($loop->first) open @endif>
                    <summary>
                        <div>
                            <div class="role-title">{{ $displayRole($roleName) }}</div>
                            <div class="role-subtitle">{{ number_format($roleUsers->count()) }} user account(s)</div>
                        </div>

                        <span class="role-stat active">Active: {{ number_format($roleActive) }}</span>
                        <span class="role-stat locked">Locked: {{ number_format($roleLocked) }}</span>
                        <span class="role-stat password">Password: {{ number_format($rolePassword) }}</span>
                        <span class="role-toggle">⌄</span>
                    </summary>

                    <div class="role-table-wrap">
                        <table class="role-table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Roles</th>
                                    <th>Status</th>
                                    <th>Password</th>
                                    <th style="text-align:right;">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($roleUsers as $user)
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
                                                    <span class="role-badge">{{ str_replace('-', ' ', $role->name) }}</span>
                                                @empty
                                                    <span class="role-badge">No role</span>
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

                                        <td style="text-align:right;">
                                            <div class="user-actions" style="justify-content:flex-end;">
                                                @if(Route::has('admin.users.show'))
                                                    <a href="{{ route('admin.users.show', $user) }}" class="action-btn action-view">View</a>
                                                @endif

                                                @if(Route::has('admin.users.edit'))
                                                    <a href="{{ route('admin.users.edit', $user) }}" class="action-btn action-edit">Edit</a>
                                                @endif

                                                @if(Route::has('admin.users.lock'))
                                                    <form method="POST" action="{{ route('admin.users.lock', $user) }}" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="action-btn action-lock">Lock</button>
                                                    </form>
                                                @endif

                                                @if(Route::has('admin.users.toggle-status'))
                                                    <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="action-btn action-view">Toggle</button>
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
                                                        <button
                                                        type="submit"
                                                        class="action-btn action-danger"
                                                        data-confirm-delete
                                                        data-delete-title="Delete User"
                                                        data-delete-message="Are you sure you want to delete this user account? This action cannot be undone."
                                                    >
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
                </details>
            @empty
                <div class="empty-state">No users found.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
