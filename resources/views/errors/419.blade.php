<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Session Expired | SPESSE-CE ABU</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(255,255,255,.14), transparent 30%),
                linear-gradient(135deg, #064532 0%, #07533f 48%, #043525 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px;
            color: #0f172a;
        }

        .expired-card {
            width: 100%;
            max-width: 620px;
            background: #ffffff;
            border-radius: 30px;
            padding: 38px;
            box-shadow: 0 28px 80px rgba(0,0,0,.28);
            text-align: center;
        }

        .logo-box {
            width: 104px;
            height: 104px;
            margin: 0 auto 18px;
            border-radius: 26px;
            background: #ffffff;
            border: 1px solid #dbe7e2;
            box-shadow: 0 14px 34px rgba(15,23,42,.12);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .logo-box img {
            width: 96px;
            height: 96px;
            object-fit: contain;
            display: block;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            padding: 9px 15px;
            border-radius: 999px;
            background: #fff7ed;
            border: 1px solid #fed7aa;
            color: #c2410c;
            font-size: .88rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .expired-title {
            margin: 0;
            font-size: 2rem;
            line-height: 1.12;
            font-weight: 950;
            color: #0f172a;
        }

        .expired-message {
            margin: 14px auto 0;
            max-width: 520px;
            color: #64748b;
            font-size: 1rem;
            line-height: 1.65;
            font-weight: 650;
        }

        .expired-note {
            margin-top: 22px;
            padding: 15px 17px;
            border-radius: 18px;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            color: #475569;
            font-size: .94rem;
            line-height: 1.55;
            font-weight: 650;
        }

        .actions {
            margin-top: 28px;
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            border: none;
            border-radius: 16px;
            padding: 13px 20px;
            font-size: .95rem;
            font-weight: 900;
            text-decoration: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 150px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0b6b57, #0f8f73);
            color: #ffffff;
            box-shadow: 0 14px 28px rgba(11,107,87,.24);
        }

        .btn-light {
            background: #ffffff;
            color: #0f172a;
            border: 1px solid #dbe3ea;
        }

        .small-text {
            margin-top: 20px;
            color: #94a3b8;
            font-size: .82rem;
            font-weight: 700;
        }

        @media (max-width: 540px) {
            body {
                padding: 18px;
            }

            .expired-card {
                padding: 28px 22px;
                border-radius: 24px;
            }

            .expired-title {
                font-size: 1.55rem;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    @php
        $user = auth()->user();

        $dashboardRoute = 'login';

        if ($user) {
            if ($user->hasRole('participant')) {
                $dashboardRoute = 'participant.dashboard';
            } else {
                $dashboardRoute = 'admin.dashboard';
            }
        }
    @endphp

    <div class="expired-card">
        <div class="logo-box">
            <img src="{{ asset('assets/images/centre-logo.png') }}" alt="SPESSE-CE ABU">
        </div>

        <div class="status-badge">
            Session Expired
        </div>

        <h1 class="expired-title">Your session has expired</h1>

        <div class="expired-message">
            For your security, this page expired because it was inactive for some time or the form token became invalid.
        </div>

        <div class="expired-note">
            Please refresh the page or return to your dashboard before submitting the form again. This helps protect your account and prevents duplicate or unsafe submissions.
        </div>

        <div class="actions">
            @if(Route::has($dashboardRoute))
                <a href="{{ route($dashboardRoute) }}" class="btn btn-primary">
                    Go to Dashboard
                </a>
            @endif

            <button type="button" onclick="window.location.reload()" class="btn btn-light">
                Refresh Page
            </button>
        </div>

        <div class="small-text">
            SPESSE-CE ABU Track A & B Attendance System
        </div>
    </div>
</body>
</html>
