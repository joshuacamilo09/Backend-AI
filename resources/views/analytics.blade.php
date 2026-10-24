<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>Admin Analytics - BackendAI</title>

    <link rel="shortcut icon" href="{{ asset('frontend/assets/icons/network.png') }}" type="image/x-icon">


    <link rel="stylesheet" href="{{ asset('frontend/css/global-app/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/global-app/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/global-app/layout-base.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/global-app/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/global-app/topbar.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/global-app/components.css') }}">

    <link rel="stylesheet" href="{{ asset('frontend/css/pages/admin-analytics.css') }}">

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
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

    <main class="content admin-analytics">
        <header class="topbar">
            <div class="search">
                <span class="search-icon">
                    <img src="{{ asset('frontend/assets/icons/search.png') }}" alt="" />
                </span>
                <input type="text" placeholder="Search metrics..." disabled />
            </div>

            <a href="{{ route('generate-backend') }}" class="primary" style="text-decoration:none;">
                <img src="{{ asset('frontend/assets/icons/add.png') }}" alt="" class="btn-icon" />
                New Generation
            </a>
        </header>

        <section class="hero">
            <div class="hero-text">
                <h1>Admin <span>Analytics</span></h1>
                <p>Advanced platform metrics, SIG data, performance insights and usage analytics.</p>
            </div>
        </section>

        <div class="projects admin-analytics-shell">
            <div class="projects-header">
                <h2>Analytics Dashboard</h2>
                <span class="badge" id="analyticsModeBadge">Live</span>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-label">Total Users</span>
                        <span class="stat-delta positive">Global</span>
                    </div>
                    <div class="stat-value" id="totalUsersValue">0</div>
                    <div class="stat-bar"><div class="stat-fill" style="width:78%"></div></div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-label">Projects Generated</span>
                        <span class="stat-delta positive">Global</span>
                    </div>
                    <div class="stat-value" id="totalProjectsValue">0</div>
                    <div class="stat-bar"><div class="stat-fill" style="width:62%"></div></div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-label">Total Generations</span>
                        <span class="stat-delta positive">Global</span>
                    </div>
                    <div class="stat-value" id="totalGenerationsValue">0</div>
                    <div class="stat-bar"><div class="stat-fill" style="width:91%"></div></div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-label">Error Rate</span>
                        <span class="stat-delta negative">Errors</span>
                    </div>
                    <div class="stat-value" id="errorRateValue">0%</div>
                    <div class="stat-bar"><div class="stat-fill danger" style="width:10%"></div></div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-label">Success Rate</span>
                        <span class="stat-delta positive">Success</span>
                    </div>
                    <div class="stat-value" id="successRateValue">0%</div>
                    <div class="stat-bar"><div class="stat-fill success" style="width:90%"></div></div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-label">Download Rate</span>
                        <span class="stat-delta positive">ZIP</span>
                    </div>
                    <div class="stat-value" id="downloadRateValue">0%</div>
                    <div class="stat-bar"><div class="stat-fill" style="width:60%"></div></div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-label">Avg Generation Time</span>
                        <span class="stat-delta positive">Performance</span>
                    </div>
                    <div class="stat-value" id="avgGenerationTimeValue">0s</div>
                    <div class="stat-bar"><div class="stat-fill" style="width:43%"></div></div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-label">Avg Download Time</span>
                        <span class="stat-delta positive">Performance</span>
                    </div>
                    <div class="stat-value" id="avgDownloadTimeValue">0s</div>
                    <div class="stat-bar"><div class="stat-fill" style="width:18%"></div></div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-label">Avg Documentation Time</span>
                        <span class="stat-delta neutral">Docs</span>
                    </div>
                    <div class="stat-value" id="avgDocumentationTimeValue">0ms</div>
                    <div class="stat-bar"><div class="stat-fill" style="width:48%"></div></div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-label">Avg Project Size</span>
                        <span class="stat-delta neutral">ZIP</span>
                    </div>
                    <div class="stat-value" id="avgProjectSizeValue">0 MB</div>
                    <div class="stat-bar"><div class="stat-fill" style="width:55%"></div></div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-label">Avg Endpoints/Project</span>
                        <span class="stat-delta positive">API</span>
                    </div>
                    <div class="stat-value" id="avgEndpointsValue">0</div>
                    <div class="stat-bar"><div class="stat-fill" style="width:48%"></div></div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-label">API Test Rate</span>
                        <span class="stat-delta positive">Tests</span>
                    </div>
                    <div class="stat-value" id="apiTestRateValue">0%</div>
                    <div class="stat-bar"><div class="stat-fill" style="width:38%"></div></div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-label">Abandonment Rate</span>
                        <span class="stat-delta negative">14 days</span>
                    </div>
                    <div class="stat-value" id="abandonmentRateValue">0%</div>
                    <div class="stat-bar"><div class="stat-fill warning" style="width:25%"></div></div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-label">Documentation Generation</span>
                        <span class="stat-delta positive">Docs</span>
                    </div>
                    <div class="stat-value" id="documentationGenerationRateValue">0%</div>
                    <div class="stat-bar"><div class="stat-fill" style="width:60%"></div></div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-label">New Users</span>
                        <span class="stat-delta neutral">Users</span>
                    </div>
                    <div class="stat-value" id="newUsersValue">0</div>
                    <div class="stat-bar"><div class="stat-fill" style="width:35%"></div></div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-label">Returning Users</span>
                        <span class="stat-delta positive">Users</span>
                    </div>
                    <div class="stat-value" id="returningUsersValue">0</div>
                    <div class="stat-bar"><div class="stat-fill success" style="width:70%"></div></div>
                </div>
            </div>

            <div class="charts-row">
                <div class="chart-card">
                    <div class="chart-card-header">
                        <div>
                            <div class="chart-card-title">Platform Growth by Continent</div>
                            <div class="chart-card-sub">Users grouped by continent</div>
                        </div>
                    </div>
                    <div class="chart-wrap">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>

                <div class="chart-card">
                    <div class="chart-card-header">
                        <div>
                            <div class="chart-card-title">Success vs Error</div>
                            <div class="chart-card-sub">Generation outcomes</div>
                        </div>
                    </div>
                    <div class="chart-wrap">
                        <canvas id="donutChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="charts-row">
                <div class="chart-card">
                    <div class="chart-card-header">
                        <div>
                            <div class="chart-card-title">Most Used Frameworks</div>
                            <div class="chart-card-sub">By generated projects</div>
                        </div>
                    </div>
                    <div class="chart-wrap">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>

                <div class="chart-card">
                    <div class="chart-card-header">
                        <div>
                            <div class="chart-card-title">Platform Radar</div>
                            <div class="chart-card-sub">Key performance dimensions</div>
                        </div>
                    </div>
                    <div class="chart-wrap">
                        <canvas id="radarChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="chart-card full">
                <div class="chart-card-header">
                    <div>
                        <div class="chart-card-title">Activity Heatmap</div>
                        <div class="chart-card-sub">Peak usage hours by day of the week</div>
                    </div>

                    <div class="heatmap-scale">
                        <span>Low</span>
                        <div class="heatmap-gradient"></div>
                        <span>High</span>
                    </div>
                </div>

                <div class="heatmap-wrap" id="heatmap"></div>
            </div>

            <div class="charts-row">
                <div class="chart-card">
                    <div class="chart-card-header">
                        <div>
                            <div class="chart-card-title">Generation Timeline</div>
                            <div class="chart-card-sub">Daily generations over time</div>
                        </div>
                    </div>
                    <div class="chart-wrap">
                        <canvas id="timelineChart"></canvas>
                    </div>
                </div>

                <div class="chart-card">
                    <div class="chart-card-header">
                        <div>
                            <div class="chart-card-title">New vs Returning Users</div>
                            <div class="chart-card-sub">User segmentation</div>
                        </div>
                    </div>
                    <div class="chart-wrap">
                        <canvas id="retentionChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="charts-row">
                <div class="chart-card">
                    <div class="chart-card-header">
                        <div>
                            <div class="chart-card-title">Top Countries</div>
                            <div class="chart-card-sub">Users by country</div>
                        </div>
                    </div>
                    <div class="geo-list" id="topCountriesList"></div>
                </div>

                <div class="chart-card">
                    <div class="chart-card-header">
                        <div>
                            <div class="chart-card-title">Most Active Cities</div>
                            <div class="chart-card-sub">Users by city</div>
                        </div>
                    </div>
                    <div class="geo-list" id="topCitiesList"></div>
                </div>

                <div class="chart-card">
                    <div class="chart-card-header">
                        <div>
                            <div class="chart-card-title">Top Continents</div>
                            <div class="chart-card-sub">Users by continent</div>
                        </div>
                    </div>
                    <div class="geo-list" id="topContinentsList"></div>
                </div>

                <div class="chart-card">
                    <div class="chart-card-header">
                        <div>
                            <div class="chart-card-title">Top Templates</div>
                            <div class="chart-card-sub">Most used project templates</div>
                        </div>
                    </div>
                    <div class="template-list" id="topTemplatesList"></div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="{{ asset('frontend/js/admin-analytics.js') }}"></script>
</body>
</html>
