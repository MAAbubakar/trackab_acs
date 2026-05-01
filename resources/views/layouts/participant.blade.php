<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Tracks A & B Attendance') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="admin-app participant-app">
        @include('partials.participant.sidebar')

        <div class="admin-main">
            @include('partials.participant.topbar')

            <main class="admin-content-wrap">
                <div class="admin-content">
                    @if(session('success'))
                        <div class="app-alert app-alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="app-alert app-alert-danger">
                            <strong>Please fix the following:</strong>
                            <ul style="margin:8px 0 0 18px;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>
