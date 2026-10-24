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
</aside>
