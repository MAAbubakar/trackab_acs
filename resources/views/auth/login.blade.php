@extends('layouts.auth')

@section('content')
    <div class="auth-header">
        <h1 class="auth-title">Sign in</h1>
        <p class="auth-subtitle">Access the SPESSE-CE ABU Tracks A & B Attendance and Compliance System admin and participant areas.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="form-grid">
        @csrf

        <div>
            <label for="email">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
        </div>

        <div>
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password">
        </div>

        <div class="auth-row">
            <label class="auth-check">
                <input type="checkbox" name="remember">
                <span>Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="auth-link">Forgot password?</a>
            @endif
        </div>

        <div class="auth-actions">
            <button type="submit" class="btn btn-primary auth-submit">Sign in</button>
        </div>
    </form>
@endsection
