@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Edit User</h3>
            <div class="page-subtitle">Update user details and assigned roles.</div>
        </div>

        <div class="actions-inline">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="form-grid content-narrow">
                @csrf
                @method('PUT')

                <div>
                    <label for="name">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
                </div>

                <div>
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="two-col-grid">
                    <div>
                        <label for="password">New Password</label>
                        <input type="password" name="password" id="password">
                    </div>

                    <div>
                        <label for="password_confirmation">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation">
                    </div>
                </div>

                <div class="two-col-grid">
                    <div>
                        <label for="status">Status</label>
                        <select name="status" id="status" required>
                            <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="locked" {{ old('status', $user->status) === 'locked' ? 'selected' : '' }}>Locked</option>
                        </select>
                    </div>

                    <div>
                        <label>Roles</label>
                        <div class="section-stack">
                            @foreach($roles as $role)
                                <label class="check-row">
                                    <input
                                        type="checkbox"
                                        name="roles[]"
                                        value="{{ $role->name }}"
                                        class="input-auto"
                                        {{ in_array($role->name, old('roles', $user->roles->pluck('name')->toArray())) ? 'checked' : '' }}
                                    >
                                    <span>{{ $role->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div>
                    <label class="check-row">
                        <input type="hidden" name="must_change_password" value="0">
                        <input type="checkbox" name="must_change_password" value="1" class="input-auto" {{ old('must_change_password', $user->must_change_password) ? 'checked' : '' }}>
                        <span>Force password change on next login</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
        </div>
    </div>
@endsection
