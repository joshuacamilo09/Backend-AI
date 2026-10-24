<x-guest-layout>
    <style>
        .auth-form {
            display: grid;
            gap: 18px;
        }

        .auth-field {
            display: grid;
            gap: 8px;
        }

        .auth-label {
            font-size: 0.92rem;
            font-weight: 600;
            color: #e2e8f0;
            letter-spacing: -0.01em;
        }

        .auth-input {
            width: 100%;
            min-height: 50px;
            border-radius: 14px;
            border: 1px solid rgba(148, 163, 184, 0.16);
            background: rgba(15, 23, 42, 0.78);
            color: #f8fafc;
            padding: 0 15px;
            outline: none;
            transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
        }

        .auth-input::placeholder {
            color: #64748b;
        }

        .auth-input:focus {
            border-color: rgba(124, 58, 237, 0.7);
            box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.14);
            background: rgba(14, 16, 32, 0.92);
        }

        .auth-submit {
            width: 100%;
            min-height: 52px;
            border: 0;
            border-radius: 14px;
            background: #7c3aed;
            color: white;
            font-weight: 700;
            font-size: 0.96rem;
            letter-spacing: -0.01em;
            cursor: pointer;
            transition: transform .18s ease, box-shadow .18s ease, opacity .18s ease;
            box-shadow: 0 10px 28px rgba(124, 58, 237, 0.35);
        }

        .auth-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 34px rgba(124, 58, 237, 0.45);
        }

        .auth-submit:active {
            transform: translateY(0);
        }

        .auth-description {
            margin: 0 0 16px;
            color: #94a3b8;
            font-size: 0.94rem;
            line-height: 1.7;
        }

        .auth-status > div {
            border-radius: 14px;
            padding: 12px 14px;
            background: rgba(34, 197, 94, 0.10);
            border: 1px solid rgba(34, 197, 94, 0.18);
            color: #86efac;
            font-size: 0.92rem;
        }

        .auth-errors ul {
            margin: 0;
            padding-left: 18px;
            color: #fca5a5;
            font-size: 0.87rem;
            line-height: 1.55;
        }

        .auth-footer-link {
            margin-top: 6px;
            text-align: center;
            font-size: 0.92rem;
            color: #a78bfa;
        }

        .auth-footer-link a {
            color: #a78bfa;
            text-decoration: none;
            font-weight: 600;
        }

        .auth-footer-link a:hover {
            color: #c084fc;
        }
    </style>

    <p class="auth-description">
        Forgot your password? No problem. Enter your email address and we will send you a password reset link so you can choose a new one.
    </p>

    <x-auth-session-status class="auth-status" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="auth-form">
        @csrf

        <div class="auth-field">
            <label for="email" class="auth-label">Email</label>
            <input
                id="email"
                class="auth-input"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                placeholder="you@example.com"
            />
            <x-input-error :messages="$errors->get('email')" class="auth-errors" />
        </div>

        <button type="submit" class="auth-submit">
            Email Password Reset Link
        </button>

        @if (Route::has('login'))
            <div class="auth-footer-link">
                Remembered your password?
                <a href="{{ route('login') }}">Go back to login</a>
            </div>
        @endif
    </form>
</x-guest-layout>
