<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Documentation - BackendAI</title>
    <link rel="shortcut icon" href="{{ asset('frontend/assets/icons/network.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('frontend/dashboard.css') }}">
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
                    <input type="text" id="docsSearchInput" placeholder="Search projects, templates..." />
                </div>

                <a href="{{ route('generate-backend') }}" class="primary" style="text-decoration:none;">
                    <img src="{{ asset('frontend/assets/icons/add.png') }}" alt="" class="btn-icon" />
                    New Generation
                </a>
            </header>

            <section class="page-shell">
                <div class="page-header">
                    <div>
                        <h1 class="page-title">Documentation</h1>
                        <p class="page-subtitle">Everything you need to know about BackendAI.</p>
                    </div>
                </div>

                <div id="docsGrid" class="docs-grid"></div>
            </section>
        </main>
    </div>

    <script src="{{ asset('frontend/documentation.js') }}"></script>
</body>
</html>
