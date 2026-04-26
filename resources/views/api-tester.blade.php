<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>API Tester - BackendAI</title>

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
                        id="apiTesterSearchInput"
                        placeholder="Search endpoints..."
                    />
                </div>

                <a href="{{ route('generate-backend') }}" class="primary" style="text-decoration:none;">
                    <img src="{{ asset('frontend/assets/icons/add.png') }}" alt="" class="btn-icon" />
                    New Generation
                </a>
            </header>

            <section class="hero">
                <div class="hero-text">
                    <h1>API <span>Tester</span></h1>
                    <p>Test real endpoints from your generated backend projects.</p>
                </div>
            </section>

            <section class="projects">
                <div class="projects-header">
                    <h2>Internal API Testing</h2>
                    <span class="badge">Postman-like</span>
                </div>

                <div style="display:grid; grid-template-columns: 320px 1fr; gap:16px;">
                    <!-- Coluna esquerda: projetos + endpoints -->
                    <div class="card" style="display:grid; gap:16px; align-content:start;">
                        <div>
                            <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:8px;">
                                Project
                            </label>

                            <select
                                id="projectSelect"
                                style="width:100%; background:var(--surface-2); border:1px solid var(--border); border-radius:12px; padding:12px; color:var(--text); outline:none;"
                            >
                                <option value="">Loading projects...</option>
                            </select>
                        </div>

                        <div>
                            <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:8px;">
                                Base URL of running backend
                            </label>

                            <input
                                id="baseUrlInput"
                                type="url"
                                placeholder="http://127.0.0.1:9000"
                                style="width:100%; background:var(--surface-2); border:1px solid var(--border); border-radius:12px; padding:12px; color:var(--text); outline:none;"
                            />

                            <p style="font-size:12px; color:var(--muted); margin-top:8px; line-height:1.5;">
                                Run the generated backend locally and paste its URL here.
                            </p>
                        </div>

                        <div>
                            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
                                <label style="font-size:13px; color:var(--muted);">
                                    Endpoints
                                </label>

                                <span id="endpointsCountBadge" class="badge">0</span>
                            </div>

                            <div
                                id="endpointsList"
                                style="display:grid; gap:8px; max-height:420px; overflow:auto;"
                            >
                                <p style="color:var(--muted); font-size:14px;">Select a project.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Coluna direita: request + response -->
                    <div style="display:grid; gap:16px;">
                        <div class="card">
                            <div class="card-header">
                                <h3>Request</h3>
                                <span id="selectedEndpointBadge" class="badge">No endpoint selected</span>
                            </div>

                            <div style="display:grid; gap:14px; margin-top:14px;">
                                <div style="display:grid; grid-template-columns:120px 1fr; gap:10px;">
                                    <input
                                        id="methodInput"
                                        type="text"
                                        readonly
                                        placeholder="GET"
                                        style="background:var(--surface-2); border:1px solid var(--border); border-radius:12px; padding:12px; color:var(--text); outline:none;"
                                    />

                                    <input
                                        id="pathInput"
                                        type="text"
                                        placeholder="/api/books"
                                        style="background:var(--surface-2); border:1px solid var(--border); border-radius:12px; padding:12px; color:var(--text); outline:none;"
                                    />
                                </div>

                                <div>
                                    <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:8px;">
                                        Headers JSON
                                    </label>

                                    <textarea
                                        id="headersInput"
                                        rows="5"
                                        placeholder='{"Authorization":"Bearer YOUR_TOKEN"}'
                                        style="width:100%; background:var(--surface-2); border:1px solid var(--border); border-radius:12px; padding:12px; color:var(--text); resize:vertical; outline:none; font-family:monospace;"
                                    >{}</textarea>
                                </div>

                                <div>
                                    <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:8px;">
                                        Body JSON
                                    </label>

                                    <textarea
                                        id="bodyInput"
                                        rows="8"
                                        placeholder='{"name":"Example"}'
                                        style="width:100%; background:var(--surface-2); border:1px solid var(--border); border-radius:12px; padding:12px; color:var(--text); resize:vertical; outline:none; font-family:monospace;"
                                    >{}</textarea>
                                </div>

                                <button id="sendRequestBtn" class="primary" type="button">
                                    Send Request
                                </button>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h3>Response</h3>
                                <span id="responseStatusBadge" class="badge">Waiting</span>
                            </div>

                            <pre
                                id="responseOutput"
                                style="margin-top:14px; background:var(--surface-2); border:1px solid var(--border); border-radius:12px; padding:14px; color:var(--text); overflow:auto; min-height:220px; font-size:13px;"
                            >No request sent yet.</pre>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="{{ asset('frontend/js/api-tester.js') }}"></script>
</body>

</html>
