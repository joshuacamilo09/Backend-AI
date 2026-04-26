<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>Documentation - BackendAI</title>

    <link rel="shortcut icon" href="{{ asset('frontend/assets/icons/network.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('frontend/css/dashboard.css') }}">
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
                    <input
                        type="text"
                        id="documentationSearchInput"
                        placeholder="Search projects..."
                    />
                </div>

                <a href="{{ route('generate-backend') }}" class="primary" style="text-decoration:none;">
                    <img src="{{ asset('frontend/assets/icons/add.png') }}" alt="" class="btn-icon" />
                    New Generation
                </a>
            </header>

            <section class="hero">
                <div class="hero-text">
                    <h1>Project <span>Documentation</span></h1>
                    <p>Generate Markdown documentation for your backend projects.</p>
                </div>
            </section>

            <section class="projects">
                <div class="projects-header">
                    <h2>Documentation Generator</h2>
                    <span class="badge">Markdown</span>
                </div>

                <div style="display:grid; grid-template-columns: 340px 1fr; gap:16px;">
                    <div class="card" style="display:grid; gap:16px; align-content:start;">
                        <div>
                            <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:8px;">
                                Project
                            </label>

                            <select
                                id="documentationProjectSelect"
                                style="width:100%; background:var(--surface-2); border:1px solid var(--border); border-radius:12px; padding:12px; color:var(--text); outline:none;"
                            >
                                <option value="">Loading projects...</option>
                            </select>
                        </div>

                        <button id="generateDocumentationBtn" class="primary" type="button">
                            Generate Documentation
                        </button>

                       <a
    id="downloadDocumentationPdfBtn"
    href="#"
    class="primary"
    style="text-decoration:none; text-align:center; display:none;"
>
    Download PDF
</a>

                        <div class="card" style="background:var(--surface-2); padding:14px;">
                            <h3 style="margin-bottom:8px;">What is included?</h3>
                            <p style="color:var(--muted); font-size:13px; line-height:1.6;">
                                The generated documentation includes installation steps,
                                database setup, project entities, API endpoints, controllers
                                and instructions for using the BackendAI API Tester.
                            </p>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3>Generated Documentation</h3>
                            <span id="documentationStatusBadge" class="badge">Waiting</span>
                        </div>

                        <div
    id="documentationOutput"
    class="markdown-preview"
>
    Select a project and generate documentation.
</div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="{{ asset('frontend/js/documentation.js') }}"></script>
</body>
</html>
