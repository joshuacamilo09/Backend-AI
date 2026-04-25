<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Templates - BackendAI</title>
    <link rel="shortcut icon" href="{{ asset('frontend/assets/icons/network.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('frontend/css/dashboard.css') }}" />
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

        <main class="content">
            <header class="topbar">
                <div class="search">
                    <span class="search-icon">
                        <img src="{{ asset('frontend/assets/icons/search.png') }}" alt="" />
                    </span>
                    <input type="text" id="templatesSearchInput" placeholder="Search projects, templates..." />
                </div>

                <a href="{{ route('generate-backend') }}" class="primary" style="text-decoration:none;">
                    <img src="{{ asset('frontend/assets/icons/add.png') }}" alt="" class="btn-icon" />
                    New Generation
                </a>
            </header>

            <section class="page-shell">
                <div class="page-header">
                    <div>
                        <h1 class="page-title">Templates</h1>
                        <p class="page-subtitle">Start from a pre-built backend template.</p>
                    </div>
                </div>

                <div id="templatesGrid" class="template-grid"></div>
            </section>
        </main>
    </div>

    <script src="{{ asset('frontend/js/templates.js') }}"></script>
</body>
</html>
