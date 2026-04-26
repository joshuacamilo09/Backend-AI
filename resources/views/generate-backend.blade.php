<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" href="{{ asset('frontend/assets/icons/network.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('frontend/css/dashboard.css') }}" />
    <title>Generate Backend - BackendAI</title>
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
        <aside class="sidebar">
            <a href="{{ url('/') }}" class="logo">
                <img src="{{ asset('frontend/assets/icons/network.png') }}" alt="" class="logo-icon" />
                Backend<span>AI</span>
            </a>

            <nav class="menu">
                <a href="{{ route('dashboard') }}"
                    class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <img src="{{ asset('frontend/assets/icons/dashboard.png') }}" alt="" class="icon" />
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('generate-backend') }}"
                    class="menu-item {{ request()->routeIs('generate-backend') ? 'active' : '' }}">
                    <img src="{{ asset('frontend/assets/icons/wand stars.png') }}" alt="" class="icon" />
                    <span>Generate Backend</span>
                </a>

                <a href="{{ route('projects') }}"
                    class="menu-item {{ request()->routeIs('projects') ? 'active' : '' }}">
                    <img src="{{ asset('frontend/assets/icons/folder.png') }}" alt="" class="icon" />
                    <span>Projects</span>
                </a>

                <a href="{{ route('templates') }}"
                    class="menu-item {{ request()->routeIs('templates') ? 'active' : '' }}">
                    <img src="{{ asset('frontend/assets/icons/templates.png') }}" alt="" class="icon" />
                    <span>Templates</span>
                </a>

                  <a href="{{ route('api-tester') }}"
                class="menu-item {{ request()->routeIs('api-tester') ? 'active' : '' }}">
                     <img src="{{ asset('frontend/assets/icons/plug.png') }}" alt="" class="icon" />
                     <span>API Tester</span>
                </a>

                   @if (auth()->user()?->role === 'admin')
    <a href="{{ route('analytics') }}"
        class="menu-item {{ request()->routeIs('analytics') ? 'active' : '' }}">
        <img src="{{ asset('frontend/assets/icons/chart.png') }}" alt="" class="icon" />
        <span>Analytics</span>
    </a>
@endif

                <a href="{{ route('settings') }}"
                    class="menu-item {{ request()->routeIs('settings') ? 'active' : '' }}">
                    <img src="{{ asset('frontend/assets/icons/settings.png') }}" alt="" class="icon" />
                    <span>Settings</span>
                </a>
            </nav>

            <div class="user-card">
                <div class="avatar" id="userAvatar"></div>
                <div class="user-meta">
                    <div class="name" id="userName"></div>
                    <div class="role" id="userRole"></div>
                </div>
            </div>

            <div class="footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        style="background:none;border:none;color:inherit;cursor:pointer;font:inherit;">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="content">
            <header class="topbar">
                <div class="search">
                    <span class="search-icon">
                        <img src="{{ asset('frontend/assets/icons/search.png') }}" alt="" />
                    </span>
                    <input type="text" placeholder="Describe what you want to build..." disabled />
                </div>

                <a href="{{ route('projects') }}" class="primary" style="text-decoration:none;">
                    <img src="{{ asset('frontend/assets/icons/folder.png') }}" alt="" class="btn-icon" />
                    View Projects
                </a>
            </header>

            <section class="hero">
                <div class="hero-text">
                    <h1>Generate a new <span>backend</span></h1>
                    <p>Describe your API and let BackendAI generate the project structure automatically.</p>
                </div>
            </section>

            <section class="projects">
                <div class="projects-header">
                    <h2>Backend Generator</h2>
                    <span class="badge">AI powered</span>
                </div>

                <div style="display:grid; gap:16px;">
                    <div class="card" style="padding:0;">
                        <div style="padding:18px 20px;">
                            <label for="backendDescription"
                                style="display:block; font-size:13px; color:var(--muted); margin-bottom:10px;">
                                Project description
                            </label>

                            <textarea id="backendDescription" rows="10"
                                placeholder="Example: I need a task management system with users, projects, tasks and comments. It should include authentication, CRUD and API documentation."
                                style="width:100%; background:var(--surface-2); border:1px solid var(--border); border-radius:12px; padding:14px; color:var(--text); resize:vertical; outline:none; font:inherit;"></textarea>

                            <div
                                style="display:flex; justify-content:space-between; align-items:center; gap:16px; margin-top:16px; flex-wrap:wrap;">
                                <p style="font-size:12px; color:var(--muted);">
                                    The generated backend will be linked to your account and downloadable as a ZIP.
                                </p>

                                <button class="primary" id="generateBackendSubmit" type="button">
                                    <img src="{{ asset('frontend/assets/icons/add.png') }}" alt="" class="btn-icon" />
                                    Generate Backend
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3>Latest generation result</h3>
                            <span class="badge" id="generateStatusBadge">Waiting</span>
                        </div>

                        <div id="generateResult" style="font-size:14px; color:var(--text-secondary);">
                            No generation yet.
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="{{ asset('frontend/js/generate-backend.js') }}"></script>
</body>

</html>
