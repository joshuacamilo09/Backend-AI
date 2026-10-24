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

        .auth-check-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
        }

        .auth-check {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: #94a3b8;
            font-size: 0.92rem;
        }

        .auth-check input {
            width: 16px;
            height: 16px;
            accent-color: #7c3aed;
        }

        .auth-link {
            color: #a78bfa;
            font-size: 0.9rem;
            text-decoration: none;
            transition: color .18s ease;
        }

        .auth-link:hover {
            color: #c084fc;
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

        .auth-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #64748b;
            font-size: 0.88rem;
            margin-top: 4px;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: rgba(148, 163, 184, 0.14);
        }

        .auth-footer {
            margin-top: 4px;
            text-align: center;
            color: #94a3b8;
            font-size: 0.92rem;
        }

        .auth-footer a {
            color: #a78bfa;
            text-decoration: none;
            font-weight: 600;
        }

        .auth-footer a:hover {
            color: #c084fc;
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
    </style>

    <x-auth-session-status class="auth-status" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="auth-form">
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
                autocomplete="username"
                placeholder="you@example.com"
            />
            <x-input-error :messages="$errors->get('email')" class="auth-errors" />
        </div>

        <div class="auth-field">
            <label for="password" class="auth-label">Password</label>
            <input
                id="password"
                class="auth-input"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Enter your password"
            />
            <x-input-error :messages="$errors->get('password')" class="auth-errors" />
        </div>

        <div class="auth-check-row">
            <label for="remember_me" class="auth-check">
                <input id="remember_me" type="checkbox" name="remember">
                <span>Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a class="auth-link" href="{{ route('password.request') }}">
                    Forgot your password?
                </a>
            @endif
        </div>

        <button type="submit" class="auth-submit">
            Log in
        </button>

        @if (Route::has('register'))
            <div class="auth-divider">or</div>

            <div class="auth-footer">
                Don’t have an account?
                <a href="{{ route('register') }}">Create one</a>
            </div>
        @endif
    </form>
</x-guest-layout>
