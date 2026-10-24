<x-guest-layout>
    <style>
        .auth-container {
            display: grid;
            gap: 20px;
        }

        .auth-description {
            color: #94a3b8;
            font-size: 0.94rem;
            line-height: 1.7;
        }

        .auth-success {
            padding: 12px 14px;
            border-radius: 12px;
            background: rgba(34,197,94,0.08);
            border: 1px solid rgba(34,197,94,0.18);
            color: #86efac;
            font-size: 0.9rem;
        }

        .auth-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .auth-button {
            min-height: 48px;
            border: 0;
            border-radius: 14px;
            background: linear-gradient(135deg,#2563eb 0%,#3b82f6 100%);
            color: white;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 0 20px;
            cursor: pointer;
            transition: transform .18s ease, box-shadow .18s ease;
            box-shadow: 0 10px 24px rgba(37,99,235,0.28);
        }

        .auth-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 30px rgba(37,99,235,0.34);
        }

        .auth-logout {
            font-size: 0.9rem;
            color: #94a3b8;
            text-decoration: none;
            border: none;
            background: none;
            cursor: pointer;
        }

        .auth-logout:hover {
            color: #e2e8f0;
        }
    </style>

    <div class="auth-container">

        <p class="auth-description">
            Thanks for signing up! Before getting started, please verify your email
            by clicking the link we sent to you. If you didn’t receive the email,
            we can send another one.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="auth-success">
                A new verification link has been sent to your email address.
            </div>
        @endif

        <div class="auth-actions">

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="auth-button">
                    Resend Verification Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="auth-logout">
                    Log out
                </button>
            </form>

        </div>

    </div>
</x-guest-layout>
