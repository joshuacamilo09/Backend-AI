<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="{{ asset('frontend/assets/icons/network.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('frontend/dashboard.css') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <title>Settings - BackendAI</title>
    <style>
        .settings-wrap {
            font-family: 'DM Sans', sans-serif;
        }

        /* ── Hero ── */
        .settings-hero {
            padding: 36px 0 28px;
            border-bottom: 1px solid var(--border-color, rgba(255,255,255,0.08));
            margin-bottom: 32px;
        }
        .settings-hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--text-secondary);
            margin-bottom: 10px;
        }
        .settings-hero-eyebrow span.dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--accent-color, #6366f1);
            display: inline-block;
        }
        .settings-hero h1 {
            font-size: 28px;
            font-weight: 300;
            letter-spacing: -0.02em;
            margin: 0 0 6px;
            line-height: 1.2;
        }
        .settings-hero h1 strong {
            font-weight: 500;
        }
        .settings-hero p {
            font-size: 14px;
            color: var(--text-secondary);
            margin: 0;
            font-weight: 300;
        }

        /* ── Section label ── */
        .settings-section-label {
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            color: var(--text-secondary);
            margin-bottom: 14px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--border-color, rgba(255,255,255,0.06));
        }

        /* ── Cards ── */
        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
            margin-bottom: 32px;
        }
        .s-card {
            background: var(--card-bg, rgba(255,255,255,0.03));
            border: 1px solid var(--border-color, rgba(255,255,255,0.08));
            border-radius: 14px;
            padding: 20px 22px;
            transition: border-color 0.2s;
        }
        .s-card:hover {
            border-color: var(--border-hover, rgba(255,255,255,0.15));
        }
        .s-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
        }
        .s-card-title {
            font-size: 13px;
            font-weight: 500;
            letter-spacing: -0.01em;
        }
        .s-card-badge {
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 3px 8px;
            border-radius: 20px;
            border: 1px solid var(--border-color, rgba(255,255,255,0.1));
            color: var(--text-secondary);
        }

        /* ── Info rows ── */
        .info-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 9px 0;
            border-bottom: 1px solid var(--border-color, rgba(255,255,255,0.05));
            gap: 12px;
        }
        .info-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .info-row:first-child {
            padding-top: 0;
        }
        .info-label {
            font-size: 12px;
            color: var(--text-secondary);
            white-space: nowrap;
        }
        .info-value {
            font-size: 13px;
            font-weight: 400;
            text-align: right;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 180px;
            font-family: 'DM Mono', monospace;
        }
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            font-weight: 500;
            color: #4ade80;
        }
        .status-pill::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #4ade80;
            box-shadow: 0 0 6px rgba(74, 222, 128, 0.5);
        }

        /* ── Action links ── */
        .action-list {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .action-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 13px;
            color: var(--text-primary);
            text-decoration: none;
            border: 1px solid transparent;
            transition: background 0.15s, border-color 0.15s;
            cursor: pointer;
            background: transparent;
            width: 100%;
            font-family: 'DM Sans', sans-serif;
            font-weight: 400;
        }
        .action-item:hover {
            background: var(--hover-bg, rgba(255,255,255,0.05));
            border-color: var(--border-color, rgba(255,255,255,0.08));
            text-decoration: none;
            color: var(--text-primary);
        }
        .action-item .arrow {
            font-size: 14px;
            color: var(--text-secondary);
            transition: transform 0.15s;
        }
        .action-item:hover .arrow {
            transform: translateX(3px);
        }
        .action-item.danger {
            color: #f87171;
        }
        .action-item.danger:hover {
            background: rgba(248, 113, 113, 0.06);
            border-color: rgba(248, 113, 113, 0.15);
        }
        .action-item.danger .arrow {
            color: #f87171;
        }

        /* ── Topbar refinements ── */
        .settings-edit-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            font-weight: 500;
            font-family: 'DM Sans', sans-serif;
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 8px;
            background: var(--primary-bg, rgba(99,102,241,0.12));
            color: var(--accent-color, #818cf8);
            border: 1px solid var(--accent-border, rgba(99,102,241,0.25));
            transition: background 0.15s, border-color 0.15s;
        }
        .settings-edit-btn:hover {
            background: var(--primary-bg-hover, rgba(99,102,241,0.2));
            border-color: rgba(99,102,241,0.4);
            text-decoration: none;
            color: var(--accent-color, #818cf8);
        }
        .settings-edit-btn img {
            width: 14px;
            height: 14px;
            opacity: 0.7;
        }

        /* hide the default .primary in the header for this page */
        .content > .topbar a.primary {
            display: none;
        }
    </style>
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

    <script src="{{ asset('frontend/settings.js') }}"></script>
</body>
</html>
