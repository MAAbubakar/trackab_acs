@extends('layouts.auth')

@section('content')
    <div class="auth-header">
        <h1 class="auth-title">Change Your Password</h1>
        <p class="auth-subtitle">For security reasons, you must set a new password before continuing.</p>
    </div>

    <form method="POST" action="{{ route('password.force.update') }}" class="form-grid">
        @csrf

        <div>
            <label for="password">New Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password">
        </div>

        <div>
            <label for="password_confirmation">Confirm New Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
        </div>

        <div class="auth-actions">
            <button type="submit" class="btn btn-primary auth-submit">Update Password</button>
        </div>
    </form>
@endsection
