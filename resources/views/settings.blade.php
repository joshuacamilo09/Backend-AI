<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="{{ asset('frontend/assets/icons/network.png') }}" type="image/x-icon">

   <link rel="stylesheet" href="{{ asset('frontend/css/global-app/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/global-app/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/global-app/layout-base.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/global-app/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/global-app/topbar.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/global-app/components.css') }}">

    <link rel="stylesheet" href="{{ asset('frontend/css/pages/settings.css') }}">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    <title>Settings - BackendAI</title>

</head>
<body>
    <script>
        window.BackendAIUser = {
            name: "{{ auth()->user()->name }}",
            email: "{{ auth()->user()->email }}",
            initials: "{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}"
        };
    </script>

    <div class="dashboard">
        @include('partials.sidebar')

        <main class="content settings-wrap">
            <header class="topbar">
                <div class="search">
                    <span class="search-icon">
                        <img src="{{ asset('frontend/assets/icons/search.png') }}" alt="" />
                    </span>
                    <input type="text" placeholder="Search settings..." disabled />
                </div>

                <a href="{{ route('profile.edit') }}" class="settings-edit-btn">
                    <img src="{{ asset('frontend/assets/icons/settings.png') }}" alt="" />
                    Edit Profile
                </a>
            </header>

            {{-- Hero --}}
            <section class="settings-hero">
                <div class="settings-hero-eyebrow">
                    <span class="dot"></span>
                    BackendAI
                </div>
                <h1>Account <strong>Settings</strong></h1>
                <p>Manage your account and workspace preferences.</p>
            </section>

            {{-- Cards --}}
            <div class="settings-section-label">Overview</div>

            <div class="settings-grid">

                {{-- User Info --}}
                <div class="s-card">
                    <div class="s-card-header">
                        <span class="s-card-title">User Information</span>
                        <span class="s-card-badge">Account</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Name</span>
                        <span class="info-value">{{ auth()->user()->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ auth()->user()->email }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status</span>
                        <span class="status-pill">Authenticated</span>
                    </div>
                </div>

                {{-- Security --}}
                <div class="s-card">
                    <div class="s-card-header">
                        <span class="s-card-title">Security</span>
                        <span class="s-card-badge">Profile</span>
                    </div>
                    <div class="action-list">
                        <a href="{{ route('profile.edit') }}" class="action-item">
                            Profile settings
                            <span class="arrow">→</span>
                        </a>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="s-card">
                    <div class="s-card-header">
                        <span class="s-card-title">Quick Actions</span>
                        <span class="s-card-badge">Access</span>
                    </div>
                    <div class="action-list">
                        <a href="{{ route('generate-backend') }}" class="action-item">
                            Generate new backend
                            <span class="arrow">→</span>
                        </a>
                        <a href="{{ route('projects') }}" class="action-item">
                            View all projects
                            <span class="arrow">→</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                            @csrf
                            <button type="submit" class="action-item danger">
                                Sign out
                                <span class="arrow">→</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script src="{{ asset('frontend/js/settings.js') }}"></script>
</body>
</html>
