<x-guest-layout>
    <style>
        body {
            background:
                radial-gradient(circle at top left, rgba(255,255,255,.10), transparent 28%),
                linear-gradient(135deg, #064532 0%, #07533f 45%, #043525 100%) !important;
        }

        .auth-page-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 18px;
        }

        .forgot-card {
            width: 100%;
            max-width: 520px;
            background: #ffffff;
            border-radius: 28px;
            padding: 34px;
            box-shadow: 0 28px 70px rgba(0,0,0,.24);
            border: 1px solid rgba(255,255,255,.55);
        }

        .forgot-logo {
            width: 92px;
            height: 92px;
            border-radius: 24px;
            margin: 0 auto 18px;
            background: #ffffff;
            border: 1px solid #dbe7e2;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 14px 30px rgba(15,23,42,.10);
            overflow: hidden;
        }

        .forgot-logo img {
            width: 82px;
            height: 82px;
            object-fit: contain;
            display: block;
        }

        .forgot-title {
            margin: 0;
            text-align: center;
            font-size: 1.85rem;
            line-height: 1.1;
            font-weight: 900;
            color: #0f172a;
        }

        .forgot-subtitle {
            margin-top: 10px;
            text-align: center;
            font-size: .98rem;
            line-height: 1.55;
            color: #64748b;
            font-weight: 600;
        }

        .forgot-system-name {
            margin-top: 8px;
            text-align: center;
            color: #0b6b57;
            font-weight: 900;
            font-size: .92rem;
        }

        .status-message {
            margin-top: 20px;
            padding: 13px 15px;
            border-radius: 16px;
            background: #ecfdf5;
            border: 1px solid #b7e4d2;
            color: #0b6b57;
            font-weight: 700;
            font-size: .92rem;
        }

        .forgot-form {
            margin-top: 26px;
        }

        .form-label {
            display: block;
            font-size: .9rem;
            font-weight: 900;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            border: 1px solid #dbe3ea;
            border-radius: 16px;
            padding: 14px 15px;
            font-size: 1rem;
            color: #0f172a;
            outline: none;
            transition: all .2s ease;
            background: #f8fafc;
            box-sizing: border-box;
        }

        .form-input:focus {
            border-color: #0b6b57;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(11,107,87,.10);
        }

        .input-error {
            margin-top: 8px;
            color: #b91c1c;
            font-size: .86rem;
            font-weight: 700;
        }

        .forgot-actions {
            margin-top: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
        }

        .back-link {
            color: #475569;
            font-weight: 800;
            text-decoration: none;
            font-size: .92rem;
        }

        .back-link:hover {
            color: #0b6b57;
        }

        .submit-btn {
            border: none;
            border-radius: 16px;
            padding: 13px 20px;
            background: linear-gradient(135deg, #0b6b57, #0f8f73);
            color: #ffffff;
            font-weight: 900;
            font-size: .95rem;
            cursor: pointer;
            box-shadow: 0 14px 28px rgba(11,107,87,.24);
            transition: all .2s ease;
        }

        .submit-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 34px rgba(11,107,87,.30);
        }

        .forgot-note {
            margin-top: 22px;
            padding: 14px 16px;
            border-radius: 16px;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            color: #64748b;
            font-size: .88rem;
            line-height: 1.5;
            font-weight: 600;
        }
    </style>

    <div class="auth-page-wrap">
        <div class="forgot-card">
            <div class="forgot-logo">
                <img src="{{ asset('assets/images/centre-logo.png') }}" alt="SPESSE-CE ABU">
            </div>

            <h1 class="forgot-title">Forgot Password?</h1>

            <div class="forgot-system-name">
                SPESSE-CE ABU Attendance System
            </div>

            <div class="forgot-subtitle">
                Enter your email address and we will send you a password reset link to create a new password.
            </div>

            @if (session('status'))
                <div class="status-message">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="forgot-form">
                @csrf

                <div>
                    <label for="email" class="form-label">Email Address</label>

                    <input
                        id="email"
                        class="form-input"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Enter your registered email address"
                    >

                    @error('email')
                        <div class="input-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="forgot-actions">
                    <a href="{{ route('login') }}" class="back-link">
                        ← Back to login
                    </a>

                    <button type="submit" class="submit-btn">
                        Email Password Reset Link
                    </button>
                </div>
            </form>

            <div class="forgot-note">
                If you do not receive the reset email, confirm that the email address is registered on the system or contact the administrator.
            </div>
        </div>
    </div>
</x-guest-layout>
