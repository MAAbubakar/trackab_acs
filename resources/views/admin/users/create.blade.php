@extends('layouts.admin')

@section('title', 'Create User')

@section('content')
<style>
    .user-form-page {
        padding: 1.5rem;
    }

    .user-form-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .user-form-title {
        font-size: 2rem;
        font-weight: 900;
        color: #102033;
        margin-bottom: .35rem;
    }

    .user-form-subtitle {
        color: #64748b;
        font-size: 1rem;
        margin-bottom: 0;
    }

    .user-card {
        background: #ffffff;
        border: 1px solid #dbe7e2;
        border-radius: 1.35rem;
        box-shadow: 0 12px 34px rgba(15, 23, 42, .07);
        padding: 1.5rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.15rem 1.25rem;
    }

    .form-full {
        grid-column: 1 / -1;
    }

    .form-label-strong {
        font-weight: 850;
        color: #0f172a;
        margin-bottom: .45rem;
        display: block;
    }

    .form-control-soft,
    .form-select-soft {
        width: 100%;
        border: 1px solid #cfe1dc;
        border-radius: .85rem;
        padding: .85rem 1rem;
        color: #0f172a;
        background: #fff;
        font-size: 1rem;
        outline: none;
    }

    .form-control-soft:focus,
    .form-select-soft:focus {
        border-color: #0b6b3a;
        box-shadow: 0 0 0 .2rem rgba(11, 107, 58, .12);
    }

    .roles-panel {
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        background: #f8fafc;
        padding: 18px;
    }

    .roles-panel-title {
        font-size: 15px;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .roles-panel-subtitle {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 16px;
    }

    .roles-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .role-option {
        position: relative;
        display: block;
        margin: 0;
    }

    .role-option input[type="checkbox"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .role-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        border: 1px solid #dbe3ea;
        border-radius: 14px;
        background: #ffffff;
        padding: 14px 15px;
        cursor: pointer;
        transition: all 0.2s ease;
        min-height: 70px;
    }

    .role-card:hover {
        border-color: #9fd5c5;
        background: #f0fdf8;
        transform: translateY(-1px);
    }

    .role-card-text {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .role-card-name {
        font-size: 15px;
        font-weight: 900;
        color: #0f172a;
        line-height: 1.2;
        text-transform: capitalize;
    }

    .role-card-help {
        font-size: 12px;
        color: #64748b;
        line-height: 1.35;
    }

    .role-card-check {
        width: 23px;
        height: 23px;
        border-radius: 999px;
        border: 2px solid #cbd5e1;
        background: #fff;
        flex-shrink: 0;
        position: relative;
        transition: all 0.2s ease;
    }

    .role-option input[type="checkbox"]:checked + .role-card {
        border-color: #0b6b57;
        background: #ecfdf5;
        box-shadow: 0 0 0 3px rgba(11, 107, 87, 0.08);
    }

    .role-option input[type="checkbox"]:checked + .role-card .role-card-check {
        border-color: #0b6b57;
        background: #0b6b57;
    }

    .role-option input[type="checkbox"]:checked + .role-card .role-card-check::after {
        content: '';
        position: absolute;
        left: 6px;
        top: 2px;
        width: 6px;
        height: 11px;
        border: solid #fff;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    .password-option {
        margin-top: 1rem;
        border: 1px solid #dbe7e2;
        border-radius: 1rem;
        padding: 1rem;
        background: #f8fbfa;
        display: flex;
        align-items: center;
        gap: .8rem;
    }

    .password-option input {
        width: 18px;
        height: 18px;
    }

    .password-option label {
        margin: 0;
        font-weight: 850;
        color: #0f172a;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: .75rem;
        margin-top: 1.5rem;
        padding-top: 1.25rem;
        border-top: 1px solid #e5e7eb;
    }

    .btn-soft {
        border-radius: .9rem;
        padding: .75rem 1.1rem;
        font-weight: 850;
        border: 1px solid #dbe7e2;
        background: #fff;
        color: #0f172a;
        text-decoration: none;
    }

    .btn-soft:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    .btn-green {
        border-radius: .9rem;
        padding: .75rem 1.1rem;
        font-weight: 850;
        border: 1px solid #0b6b3a;
        background: #0b6b3a;
        color: #fff;
        box-shadow: 0 10px 20px rgba(11,107,58,.15);
    }

    .btn-green:hover {
        background: #095c32;
        color: #fff;
    }

    @media (max-width: 900px) {
        .form-grid,
        .roles-grid {
            grid-template-columns: 1fr;
        }

        .user-form-header,
        .form-actions {
            flex-direction: column;
        }

        .form-actions a,
        .form-actions button {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="user-form-page">
    <div class="user-form-header">
        <div>
            <h1 class="user-form-title">Create User</h1>
            <p class="user-form-subtitle">Create a system user and assign the right access role.</p>
        </div>

        <a href="{{ route('admin.users.index') }}" class="btn-soft">Back</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger rounded-4">
            <strong>Please correct the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <div class="user-card">
            <div class="form-grid">
                <div>
                    <label class="form-label-strong">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control-soft" required>
                </div>

                <div>
                    <label class="form-label-strong">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control-soft" required>
                </div>

                <div>
                    <label class="form-label-strong">Password</label>
                    <input type="password" name="password" class="form-control-soft" required>
                </div>

                <div>
                    <label class="form-label-strong">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control-soft" required>
                </div>

                <div>
                    <label class="form-label-strong">Status</label>
                    <select name="status" class="form-select-soft">
                        <option value="active" @selected(old('status', 'active') === 'active')>Active</option>
                        <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                        <option value="locked" @selected(old('status') === 'locked')>Locked</option>
                    </select>
                </div>

                <div class="form-full">
                    <label class="form-label-strong">Roles</label>

                    <div class="roles-panel">
                        <div class="roles-panel-title">Assign User Roles</div>
                        <div class="roles-panel-subtitle">
                            Select one or more roles for this user account.
                        </div>

                        <div class="roles-grid">
                            @foreach($roles as $role)
                                @php
                                    $roleHelp = match($role->name) {
                                        'super-admin' => 'Full system access and control.',
                                        'programme-coordinator' => 'Oversees programme operations and reporting.',
                                        'attendance-officer' => 'Handles attendance scanning and checkpoint monitoring.',
                                        'm&e-officer' => 'Tracks evaluation, reports, and compliance.',
                                        'participant' => 'Access to the participant portal only.',
                                        default => 'System role.',
                                    };
                                @endphp

                                <label class="role-option">
                                    <input
                                        type="checkbox"
                                        name="roles[]"
                                        value="{{ $role->name }}"
                                        @checked(in_array($role->name, old('roles', [])))
                                    >

                                    <span class="role-card">
                                        <span class="role-card-text">
                                            <span class="role-card-name">
                                                {{ str_replace('-', ' ', $role->name) }}
                                            </span>
                                            <span class="role-card-help">{{ $roleHelp }}</span>
                                        </span>

                                        <span class="role-card-check"></span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="password-option">
                <input
                    type="checkbox"
                    id="must_change_password"
                    name="must_change_password"
                    value="1"
                    @checked(old('must_change_password', true))
                >
                <label for="must_change_password">Force password change on first login</label>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.users.index') }}" class="btn-soft">Cancel</a>
                <button type="submit" class="btn-green">Create User</button>
            </div>
        </div>
    </form>
</div>
@endsection
