<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password | SPESSE-CE ABU</title>
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

        .auth-card {
            width: 100%;
            max-width: 560px;
            background: #ffffff;
            border-radius: 30px;
            padding: 38px;
            box-shadow: 0 28px 80px rgba(0,0,0,.28);
        }

        .auth-logo {
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

        .auth-logo img {
            width: 96px;
            height: 96px;
            object-fit: contain;
            display: block;
        }

        .auth-title {
            margin: 0;
            text-align: center;
            font-size: 2rem;
            line-height: 1.12;
            font-weight: 950;
            color: #0f172a;
        }

        .auth-subtitle {
            margin-top: 10px;
            text-align: center;
            color: #0b6b57;
            font-weight: 900;
            font-size: 1rem;
        }

        .auth-message {
            margin: 16px auto 26px;
            max-width: 460px;
            color: #64748b;
            text-align: center;
            line-height: 1.6;
            font-size: .98rem;
            font-weight: 650;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #0f172a;
            font-weight: 900;
            font-size: .92rem;
        }

        input {
            width: 100%;
            height: 54px;
            border: 1px solid #dbe3ea;
            border-radius: 17px;
            padding: 0 16px;
            background: #ffffff;
            color: #0f172a;
            font-size: 1rem;
            font-weight: 700;
            outline: none;
        }

        input:focus {
            border-color: #0b6b57;
            box-shadow: 0 0 0 4px rgba(11,107,87,.10);
        }

        .error {
            margin-top: 7px;
            color: #be123c;
            font-size: .85rem;
            font-weight: 750;
        }

        .error-box {
            margin-bottom: 18px;
            padding: 14px 16px;
            border-radius: 16px;
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #be123c;
            font-size: .9rem;
            font-weight: 750;
            line-height: 1.5;
        }

        .submit-btn {
            width: 100%;
            height: 54px;
            border: none;
            border-radius: 17px;
            background: linear-gradient(135deg, #0b6b57, #0f8f73);
            color: #ffffff;
            font-size: 1rem;
            font-weight: 950;
            cursor: pointer;
            box-shadow: 0 14px 28px rgba(11,107,87,.24);
        }

        .submit-btn:hover {
            filter: brightness(.98);
        }

        .auth-footer {
            margin-top: 22px;
            text-align: center;
            color: #94a3b8;
            font-size: .82rem;
            font-weight: 700;
        }

        @media (max-width: 540px) {
            body {
                padding: 18px;
            }

            .auth-card {
                padding: 28px 22px;
                border-radius: 24px;
            }

            .auth-title {
                font-size: 1.55rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-logo">
            <img src="{{ asset('assets/images/centre-logo.png') }}" alt="SPESSE-CE ABU">
        </div>

        <h1 class="auth-title">Reset Password</h1>

        <div class="auth-subtitle">
            SPESSE-CE ABU Attendance System
        </div>

        <div class="auth-message">
            Enter your new password below. Make sure both password fields match before submitting.
        </div>

        @if($errors->any())
            <div class="error-box">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email', $request->email) }}"
                    required
                    autofocus
                    autocomplete="username"
                >
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">New Password</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Enter new password"
                >
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Confirm new password"
                >
                @error('password_confirmation')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="submit-btn">
                Reset Password
            </button>
        </form>

        <div class="auth-footer">
            SPESSE-CE ABU Track A & B Attendance System
        </div>
    </div>
</body>
</html>
