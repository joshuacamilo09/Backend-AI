<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="{{ asset('frontend/assets/icons/network.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('frontend/css/dashboard.css') }}" />
    <title>BackendAI Dashboard</title>
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
                <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <img src="{{ asset('frontend/assets/icons/dashboard.png') }}" alt="" class="icon" />
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('generate-backend') }}" class="menu-item {{ request()->routeIs('generate-backend') ? 'active' : '' }}">
                    <img src="{{ asset('frontend/assets/icons/wand stars.png') }}" alt="" class="icon" />
                    <span>Generate Backend</span>
                </a>

                <a href="{{ route('projects') }}" class="menu-item {{ request()->routeIs('projects') ? 'active' : '' }}">
                    <img src="{{ asset('frontend/assets/icons/folder.png') }}" alt="" class="icon" />
                    <span>Projects</span>
                </a>

                <a href="{{ route('templates') }}" class="menu-item {{ request()->routeIs('templates') ? 'active' : '' }}">
                    <img src="{{ asset('frontend/assets/icons/templates.png') }}" alt="" class="icon" />
                    <span>Templates</span>
                </a>

                <a href="{{ route('api-tester') }}"
                class="menu-item {{ request()->routeIs('api-tester') ? 'active' : '' }}">
                     <img src="{{ asset('frontend/assets/icons/plug.png') }}" alt="" class="icon" />
                     <span>API Tester</span>
                </a>

                <a href="{{ route('documentation') }}"
         class="menu-item {{ request()->routeIs('documentation') ? 'active' : '' }}">
             <img src="{{ asset('frontend/assets/icons/docs.png') }}" alt="" class="icon" />
             <span>Documentation</span>
        </a>
        
                @if (auth()->user()?->role === 'admin')
    <a href="{{ route('analytics') }}"
        class="menu-item {{ request()->routeIs('analytics') ? 'active' : '' }}">
        <img src="{{ asset('frontend/assets/icons/chart.png') }}" alt="" class="icon" />
        <span>Analytics</span>
    </a>
@endif



                <a href="{{ route('settings') }}" class="menu-item {{ request()->routeIs('settings') ? 'active' : '' }}">
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
                    <button type="submit" style="background:none;border:none;color:inherit;cursor:pointer;font:inherit;">
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
                    <input type="text" placeholder="Search projects..." />
                </div>

                <a href="{{ route('generate-backend') }}" class="primary" style="text-decoration:none;">
                    <img src="{{ asset('frontend/assets/icons/add.png') }}" alt="" class="btn-icon" />
                    Create Backend
                </a>
            </header>

            <section class="hero">
                <div class="hero-text">
                    <h1>Welcome back <span id="heroUserName"></span></h1>
                    <p>Here's what's happening with your projects.</p>
                </div>
            </section>

            <section class="stats">
                <div class="card">
                    <div class="card-header">
                        <h3>Total Projects</h3>
                        <span class="badge" id="projectsBadge">0 total</span>
                    </div>
                    <p class="card-value" id="totalProjectsValue">0</p>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Total Generations</h3>
                        <span class="badge" id="generationsBadge">0 total</span>
                    </div>
                    <p class="card-value" id="totalGenerationsValue">0</p>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Last Activity</h3>
                        <span class="badge" id="lastActivityBadge">No activity</span>
                    </div>
                    <p class="card-value" id="lastActivityValue">—</p>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Most Used</h3>
                        <span class="badge" id="mostUsedBadge">Framework</span>
                    </div>
                    <p class="card-value" id="mostUsedValue">Laravel</p>
                </div>
            </section>

            <section class="projects">
                <div class="projects-header">
                    <h2>Recent Projects</h2>
                    <a href="{{ route('projects') }}" class="view-all">View all →</a>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Project</th>
                                <th>Framework</th>
                                <th>Description</th>
                                <th>Created</th>
                                <th>Status</th>
                                <th>Download</th>
                            </tr>
                        </thead>
                        <tbody id="projectsTableBody">
                            <tr>
                                <td colspan="6">Loading projects...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script src="{{ asset('frontend/js/dashboard.js') }}"></script>
</body>
</html>
