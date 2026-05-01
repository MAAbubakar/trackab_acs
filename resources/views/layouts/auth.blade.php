<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'SPESSE-CE ABU Tracks A & B Attendance and Compliance System') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-page">
    <main class="auth-shell">
        <div class="auth-card">
            <div class="auth-brand">
                <img src="{{ asset('assets/images/centre-logo.png') }}" alt="Centre Logo" class="auth-logo">

                <div>
                    <div class="auth-brand-title">SPESSE-CE ABU</div>
                    <div class="auth-brand-subtitle">Tracks A & B Attendance and Compliance System</div>
                </div>
            </div>

            @if (session('status'))
                <div class="app-alert app-alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="app-alert app-alert-danger">
                    <strong>Please fix the following:</strong>
                    <ul style="margin:8px 0 0 18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</body>
</html>
