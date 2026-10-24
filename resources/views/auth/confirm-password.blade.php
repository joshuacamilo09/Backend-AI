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
            transition: transform .18s ease, box-shadow .18s ease;
            box-shadow: 0 14px 34px rgba(124, 58, 237, 0.35);
        }

        .auth-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 34px rgba(124, 58, 237, 0.45);
        }

        .auth-errors ul {
            margin: 0;
            padding-left: 18px;
            color: #fca5a5;
            font-size: 0.87rem;
            line-height: 1.55;
        }

        .auth-description {
            margin-bottom: 16px;
            color: #94a3b8;
            font-size: 0.94rem;
            line-height: 1.7;
        }
    </style>

    <p class="auth-description">
        This is a secure area of the application. Please confirm your password before continuing.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}" class="auth-form">
        @csrf

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

        <button type="submit" class="auth-submit">
            Confirm
        </button>
    </form>
</x-guest-layout>
