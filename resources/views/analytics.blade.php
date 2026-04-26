<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>Analytics - BackendAI</title>

    <link rel="shortcut icon" href="{{ asset('frontend/assets/icons/network.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('frontend/css/dashboard.css') }}">
</head>

<body>
    <script>
        window.BackendAIUser = {
            name: "{{ auth()->user()->name }}",
            email: "{{ auth()->user()->email }}",
            initials: "{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}",
            role: "{{ auth()->user()->role }}"
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
                    <input type="text" placeholder="Analytics overview..." disabled />
                </div>

                <a href="{{ route('generate-backend') }}" class="primary" style="text-decoration:none;">
                    <img src="{{ asset('frontend/assets/icons/add.png') }}" alt="" class="btn-icon" />
                    New Generation
                </a>
            </header>

            <section class="hero">
                <div class="hero-text">
                    <h1>Platform <span>Analytics</span></h1>
                    <p>View usage insights, generation metrics and SIG-based geographic data.</p>
                </div>
            </section>

            <section class="projects">
                <div class="projects-header">
                    <h2>Analytics Dashboard</h2>
                    <span class="badge" id="analyticsModeBadge">Loading</span>
                </div>

                <section class="stats">
                    <div class="card">
                        <div class="card-header">
                            <h3>Avg Projects / User</h3>
                            <span class="badge">Average</span>
                        </div>
                        <p class="card-value" id="avgProjectsValue">—</p>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3>Avg Generations / User</h3>
                            <span class="badge">Average</span>
                        </div>
                        <p class="card-value" id="avgGenerationsValue">—</p>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3>Success Rate</h3>
                            <span class="badge">Global</span>
                        </div>
                        <p class="card-value" id="successRateValue">—</p>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3>Error Rate</h3>
                            <span class="badge">Global</span>
                        </div>
                        <p class="card-value" id="errorRateValue">—</p>
                    </div>
                </section>

                <div class="analytics-grid">
                    <div class="card">
                        <div class="card-header">
                            <h3>Generation Activity</h3>
                            <span class="badge">Last records</span>
                        </div>
                        <div id="generationsChart" class="simple-chart"></div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3>Frameworks Usage</h3>
                            <span class="badge">Top</span>
                        </div>
                        <div id="frameworksChart" class="simple-list-chart"></div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3>Documentation Rate</h3>
                            <span class="badge">Docs</span>
                        </div>
                        <div class="donut-wrap">
                            <div class="donut" id="documentationDonut">0%</div>
                            <p class="analytics-muted">Percentage of projects with generated documentation.</p>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3>Peak Activity Hour</h3>
                            <span class="badge">Time</span>
                        </div>
                        <p class="card-value" id="peakHourValue">—</p>
                        <p class="analytics-muted">Hour with most backend generations.</p>
                    </div>
                </div>

                <div id="adminAnalyticsSection" style="display:none; margin-top:16px;">
                    <div class="projects-header">
                        <h2>Admin Analytics</h2>
                        <span class="badge">Admin only</span>
                    </div>

                    <section class="stats">
                        <div class="card">
                            <div class="card-header">
                                <h3>Total Users</h3>
                                <span class="badge">Global</span>
                            </div>
                            <p class="card-value" id="totalUsersValue">—</p>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h3>Total Projects</h3>
                                <span class="badge">Global</span>
                            </div>
                            <p class="card-value" id="totalProjectsValue">—</p>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h3>Total Generations</h3>
                                <span class="badge">Global</span>
                            </div>
                            <p class="card-value" id="totalGenerationsValue">—</p>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h3>Abandonment Rate</h3>
                                <span class="badge">14 days</span>
                            </div>
                            <p class="card-value" id="abandonmentRateValue">—</p>
                        </div>
                    </section>

                    <div class="analytics-grid">
                        <div class="card">
                            <div class="card-header">
                                <h3>Countries With Most Users</h3>
                                <span class="badge">SIG</span>
                            </div>
                            <div id="countriesChart" class="simple-list-chart"></div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h3>Most Active Cities</h3>
                                <span class="badge">SIG</span>
                            </div>
                            <div id="citiesChart" class="simple-list-chart"></div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h3>Continents</h3>
                                <span class="badge">Growth</span>
                            </div>
                            <div id="continentsChart" class="simple-list-chart"></div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h3>Heatmap Points</h3>
                                <span class="badge">Map data</span>
                            </div>
                            <pre id="heatmapOutput" class="analytics-pre">No location data yet.</pre>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="{{ asset('frontend/js/analytics.js') }}"></script>
</body>

</html>
