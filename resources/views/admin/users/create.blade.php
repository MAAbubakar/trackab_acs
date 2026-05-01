@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Create User</h3>
            <div class="page-subtitle">Add a new user and assign roles.</div>
        </div>

        <div class="actions-inline">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST" class="form-grid content-narrow">
                @csrf

                <div>
                    <label for="name">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                </div>

                <div>
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required>
                </div>

                <div class="two-col-grid">
                    <div>
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" required>
                    </div>

                    <div>
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required>
                    </div>
                </div>

                <div class="two-col-grid">
                    <div>
                        <label for="status">Status</label>
                        <select name="status" id="status" required>
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="locked" {{ old('status') === 'locked' ? 'selected' : '' }}>Locked</option>
                        </select>
                    </div>

                    <div>
                        <label>Roles</label>
                        <div class="section-stack">
                            @foreach($roles as $role)
                                <label class="check-row">
                                    <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="input-auto" {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}>
                                    <span>{{ $role->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div>
                    <label class="check-row">
                        <input type="hidden" name="must_change_password" value="0">
                        <input type="checkbox" name="must_change_password" value="1" class="input-auto" {{ old('must_change_password') ? 'checked' : '' }}>
                        <span>Force password change on first login</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn btn-primary">Create User</button>
                </div>
            </form>
        </div>
    </div>
@endsection
