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
            box-shadow: 0 10px 24px rgba(124, 58, 237, 0.35);
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

        .auth-errors ul {
            margin: 0;
            padding-left: 18px;
            color: #fca5a5;
            font-size: 0.87rem;
            line-height: 1.55;
        }
    </style>

    <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf

        <input type="hidden" id="latitude" name="latitude">
<input type="hidden" id="longitude" name="longitude">

<p id="locationStatus" style="color:#94a3b8; font-size:13px; line-height:1.5;">
    Location permission helps BackendAI generate geographic analytics.
</p>

        <div class="auth-field">
            <label for="name" class="auth-label">Name</label>
            <input
                id="name"
                class="auth-input"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
                placeholder="Your full name"
            />
            <x-input-error :messages="$errors->get('name')" class="auth-errors" />
        </div>

        <div class="auth-field">
            <label for="email" class="auth-label">Email</label>
            <input
                id="email"
                class="auth-input"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
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
                autocomplete="new-password"
                placeholder="Create a password"
            />
            <x-input-error :messages="$errors->get('password')" class="auth-errors" />
        </div>

        <div class="auth-field">
            <label for="password_confirmation" class="auth-label">Confirm Password</label>
            <input
                id="password_confirmation"
                class="auth-input"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Repeat your password"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="auth-errors" />
        </div>

        <div class="auth-field">
    <label for="country" class="auth-label">Country</label>
    <input id="country" class="auth-input" type="text" name="country" value="{{ old('country') }}" placeholder="Portugal">
</div>

<div class="auth-field">
    <label for="city" class="auth-label">City</label>
    <input id="city" class="auth-input" type="text" name="city" value="{{ old('city') }}" placeholder="Lisbon">
</div>

<div class="auth-field">
    <label for="continent" class="auth-label">Continent</label>
    <select id="continent" name="continent" class="auth-input">
        <option value="">Select continent</option>
        <option value="Europe">Europe</option>
        <option value="Africa">Africa</option>
        <option value="Asia">Asia</option>
        <option value="North America">North America</option>
        <option value="South America">South America</option>
        <option value="Oceania">Oceania</option>
    </select>
</div>

<div class="auth-field">
    <label for="user_type" class="auth-label">User Type</label>
    <select id="user_type" name="user_type" class="auth-input">
        <option value="">Select user type</option>
        <option value="student">Student</option>
        <option value="junior_developer">Junior Developer</option>
        <option value="freelancer">Freelancer</option>
        <option value="teacher">Teacher</option>
        <option value="company">Company</option>
    </select>
</div>

<div class="auth-field">
    <label for="experience_level" class="auth-label">Experience Level</label>
    <select id="experience_level" name="experience_level" class="auth-input">
        <option value="">Select level</option>
        <option value="beginner">Beginner</option>
        <option value="intermediate">Intermediate</option>
        <option value="advanced">Advanced</option>
    </select>
</div>

<div class="auth-field">
    <label for="main_interest" class="auth-label">Main Interest</label>
    <select id="main_interest" name="main_interest" class="auth-input">
        <option value="">Select interest</option>
        <option value="backend">Backend</option>
        <option value="frontend">Frontend</option>
        <option value="fullstack">Fullstack</option>
        <option value="ai">AI</option>
        <option value="databases">Databases</option>
        <option value="devops">DevOps</option>
    </select>
</div>

        <button type="submit" class="auth-submit">
            Create account
        </button>

        <div class="auth-divider">or</div>

        <div class="auth-footer">
            Already registered?
            <a href="{{ route('login') }}">Log in</a>
        </div>
    </form>

    <script>
document.addEventListener("DOMContentLoaded", () => {
    const latitudeInput = document.getElementById("latitude");
    const longitudeInput = document.getElementById("longitude");
    const status = document.getElementById("locationStatus");

    // Verifica se o browser suporta Geolocation API.
    if (!navigator.geolocation) {
        if (status) {
            status.textContent = "Geolocation is not supported by this browser.";
        }
        return;
    }

    // Pede permissão ao utilizador para obter localização.
    navigator.geolocation.getCurrentPosition(
        (position) => {
            latitudeInput.value = position.coords.latitude;
            longitudeInput.value = position.coords.longitude;

            if (status) {
                status.textContent = "Location captured successfully.";
            }
        },
        () => {
            if (status) {
                status.textContent = "Location permission denied. You can still register.";
            }
        },
        {
            enableHighAccuracy: false,
            timeout: 8000,
            maximumAge: 60000,
        }
    );
});
</script>

</x-guest-layout>
