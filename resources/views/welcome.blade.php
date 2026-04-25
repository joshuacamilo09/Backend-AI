<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BackendAI</title>
    <link rel="shortcut icon" href="{{ asset('frontend/assets/icons/network.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('frontend/css/styles.css') }}" />
</head>
<body>

    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <img src="{{ asset('frontend/assets/icons/network.png') }}" alt="logo" class="nav-logo-icon">
            <a href="#home" class="nav-logo">Backend<span>AI</span></a>

            <ul class="nav-links" id="navLinks">
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#features">Features</a></li>
                <li><a href="#docs">Documentation</a></li>
            </ul>

            <div class="nav-actions">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-ghost">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost">Log in</a>
                    <a href="{{ route('register') }}" class="btn-primary">Get Started</a>
                @endauth
            </div>

            <button class="hamburger" id="hamburger" aria-label="Toggle menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>

    <section class="hero" id="home">
        <div class="hero-noise"></div>
        <div class="hero-glow"></div>

        <div class="hero-content">
            <div class="hero-badge">✦ AI-Powered Platform</div>
            <h1 class="hero-title">
                Build smarter backends<br />
                <span class="gradient-text">with the power of AI.</span>
            </h1>
            <p class="hero-desc">
                BackendAI is a modern platform designed to simplify backend development using artificial intelligence. Create,
                manage and optimize APIs, databases, and server logic faster than ever.
            </p>
            <div class="hero-cta">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary btn-lg">Start Building →</a>
                @else
                    <a href="{{ route('register') }}" class="btn-primary btn-lg">Start Building →</a>
                @endauth
                <a href="#features" class="btn-outline btn-lg">Explore Features</a>
            </div>
        </div>

        <div class="hero-visual">
            <div class="dashboard-mock">
                <div class="mock-topbar">
                    <div class="mock-dots"><span></span><span></span><span></span></div>
                    <div class="mock-title">BackendAI Dashboard</div>
                    <div class="mock-btn">+ New API</div>
                </div>

                <div class="mock-body">
                    <div class="mock-sidebar">
                        <div class="mock-nav-item active">⬡ Overview</div>
                        <div class="mock-nav-item">⚡ APIs</div>
                        <div class="mock-nav-item">⬢ Database</div>
                        <div class="mock-nav-item">◎ Automation</div>
                        <div class="mock-nav-item">◈ Settings</div>
                    </div>

                    <div class="mock-main">
                        <div class="mock-stats">
                            <div class="mock-stat">
                                <div class="stat-val">142</div>
                                <div class="stat-lbl">Active APIs</div>
                            </div>
                            <div class="mock-stat">
                                <div class="stat-val">98.9%</div>
                                <div class="stat-lbl">Uptime</div>
                            </div>
                            <div class="mock-stat">
                                <div class="stat-val">3.2ms</div>
                                <div class="stat-lbl">Avg Response</div>
                            </div>
                            <div class="mock-stat">
                                <div class="stat-val">48K</div>
                                <div class="stat-lbl">Requests/day</div>
                            </div>
                        </div>

                        <div class="mock-chart">
                            <div class="chart-label">Performance Overview</div>
                            <div class="chart-bars">
                                <div class="bar" style="--h:55%"></div>
                                <div class="bar" style="--h:72%"></div>
                                <div class="bar" style="--h:48%"></div>
                                <div class="bar" style="--h:85%"></div>
                                <div class="bar" style="--h:63%"></div>
                                <div class="bar" style="--h:91%"></div>
                                <div class="bar" style="--h:77%"></div>
                                <div class="bar active" style="--h:94%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="floating-badge">
                    <span class="badge-icon">↑</span>
                    <div>
                        <div class="badge-val">48K</div>
                        <div class="badge-sub">Daily Requests</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="about" id="about">
        <div class="about-bg-gradient"></div>
        <div class="container">
            <div class="section-label">About the Platform</div>
            <h2 class="section-title">What is BackendAI?</h2>
            <p class="section-subtitle">
                BackendAI is a development platform that combines backend architecture with artificial intelligence to
                streamline how developers build and manage applications.
            </p>

            <div class="about-grid">
                <div class="about-card" data-delay="0">
                    <div class="about-icon"><img src="{{ asset('frontend/assets/icons/settings.png') }}" alt="settings"></div>
                    <h3>Automate backend tasks</h3>
                    <p>Let AI handle repetitive operations so you focus on what matters.</p>
                </div>
                <div class="about-card" data-delay="1">
                    <div class="about-icon"><img src="{{ asset('frontend/assets/icons/plug.png') }}" alt="plug"></div>
                    <h3>Generate API structures</h3>
                    <p>Instantly scaffold RESTful APIs tailored to your data models.</p>
                </div>
                <div class="about-card" data-delay="2">
                    <div class="about-icon"><img src="{{ asset('frontend/assets/icons/database.png') }}" alt="database"></div>
                    <h3>Optimize database queries</h3>
                    <p>AI-powered query analysis and suggestions for peak performance.</p>
                </div>
                <div class="about-card" data-delay="3">
                    <div class="about-icon"><img src="{{ asset('frontend/assets/icons/monitor.png') }}" alt="desktop"></div>
                    <h3>Manage server logic</h3>
                    <p>Centralize and control all your server-side logic from one dashboard.</p>
                </div>
                <div class="about-card" data-delay="4">
                    <div class="about-icon"><img src="{{ asset('frontend/assets/icons/bot.png') }}" alt="robot"></div>
                    <h3>AI-powered integrations</h3>
                    <p>Seamlessly connect intelligent tools into your development workflow.</p>
                </div>
                <div class="about-card" data-delay="5">
                    <div class="about-icon"><img src="{{ asset('frontend/assets/icons/bolt.png') }}" alt="bolt"></div>
                    <h3>Built for speed & scale</h3>
                    <p>Engineered for scalability, simplicity, and blazing-fast delivery.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="features" id="features">
        <div class="features-gradient"></div>
        <div class="container">
            <div class="section-label">What We Offer</div>
            <h2 class="section-title">Powerful Features</h2>
            <p class="section-subtitle">Everything you need to build intelligent, scalable backend systems.</p>

            <div class="features-list">
                <div class="feature-item" data-index="0">
                    <div class="feature-num">01</div>
                    <div class="feature-icon"><img src="{{ asset('frontend/assets/icons/brain.png') }}" alt="brain"></div>
                    <div class="feature-body">
                        <h3>AI Assisted Development</h3>
                        <p>Accelerate development with AI-powered backend tools that predict, suggest, and automate complex logic generation.</p>
                    </div>
                </div>

                <div class="feature-item" data-index="1">
                    <div class="feature-num">02</div>
                    <div class="feature-icon"><img src="{{ asset('frontend/assets/icons/bolt.png') }}" alt=""></div>
                    <div class="feature-body">
                        <h3>Smart Automation</h3>
                        <p>Automate repetitive backend tasks and processes, from request handling to data pipelines and workflow management.</p>
                    </div>
                </div>

                <div class="feature-item" data-index="2">
                    <div class="feature-num">03</div>
                    <div class="feature-icon"><img src="{{ asset('frontend/assets/icons/link.png') }}" alt=""></div>
                    <div class="feature-body">
                        <h3>API Management</h3>
                        <p>Design, deploy and manage APIs with ease. Monitor endpoints, handle authentication and control requests in real-time.</p>
                    </div>
                </div>

                <div class="feature-item" data-index="3">
                    <div class="feature-num">04</div>
                    <div class="feature-icon"><img src="{{ asset('frontend/assets/icons/chart.png') }}" alt=""></div>
                    <div class="feature-body">
                        <h3>Performance Optimization</h3>
                        <p>Improve application performance with intelligent insights, monitoring tools and automated profiling recommendations.</p>
                    </div>
                </div>

                <div class="feature-item" data-index="4">
                    <div class="feature-num">05</div>
                    <div class="feature-icon"><img src="{{ asset('frontend/assets/icons/construction.png') }}" alt=""></div>
                    <div class="feature-body">
                        <h3>Scalable Architecture</h3>
                        <p>Build systems ready to scale with your application, whether you're shipping a startup MVP or a large enterprise platform.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="docs" id="docs">
        <div class="docs-bg"></div>
        <div class="container docs-container">
            <div class="docs-text">
                <div class="section-label">Documentation</div>
                <h2 class="section-title">Everything you need<br />to get started</h2>
                <p class="section-subtitle">Clear, organized documentation to help you integrate and build with BackendAI from day one.</p>

                <div class="docs-steps">
                    <div class="doc-step">
                        <div class="step-num">1</div>
                        <div>
                            <strong>Clone the repository</strong>
                            <p>Download or clone the BackendAI project to your local environment.</p>
                        </div>
                    </div>
                    <div class="doc-step">
                        <div class="step-num">2</div>
                        <div>
                            <strong>Install dependencies</strong>
                            <p>Install all required packages using your preferred package manager.</p>
                        </div>
                    </div>
                    <div class="doc-step">
                        <div class="step-num">3</div>
                        <div>
                            <strong>Configure your environment</strong>
                            <p>Set up your backend configuration, API keys and environment variables.</p>
                        </div>
                    </div>
                    <div class="doc-step">
                        <div class="step-num">4</div>
                        <div>
                            <strong>Launch the dashboard</strong>
                            <p>Start the application and access the full BackendAI dashboard interface.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="docs-visual">
                <div class="code-block">
                    <div class="code-topbar">
                        <span class="dot red"></span><span class="dot yellow"></span><span class="dot green"></span>
                        <span class="code-filename">app.js</span>
                    </div>
<pre><code><span class="c-comment">// BackendAI — API Configuration</span>
<span class="c-keyword">import</span> BackendAI <span class="c-keyword">from</span> <span class="c-string">'backendai-sdk'</span>;

<span class="c-keyword">const</span> client = <span class="c-keyword">new</span> <span class="c-fn">BackendAI</span>({
  apiKey: process.env.<span class="c-var">BACKENDAI_KEY</span>,
  region: <span class="c-string">'eu-west-1'</span>,
  ai: { <span class="c-var">autoOptimize</span>: <span class="c-bool">true</span> }
});

<span class="c-comment">// Generate a REST API endpoint</span>
<span class="c-keyword">const</span> api = <span class="c-keyword">await</span> client.<span class="c-fn">createAPI</span>({
  name: <span class="c-string">'users'</span>,
  methods: [<span class="c-string">'GET'</span>, <span class="c-string">'POST'</span>, <span class="c-string">'PATCH'</span>],
  auth: <span class="c-string">'jwt'</span>,
  cache: <span class="c-bool">true</span>
});

console.<span class="c-fn">log</span>(<span class="c-string">`✓ API ready: </span><span class="c-var">${api.url}</span><span class="c-string">`</span>);
</code></pre>
                </div>

                <div class="file-structure">
                    <div class="fs-title">Project Structure</div>
                    <div class="fs-item"><span class="fs-icon">📄</span> index.html</div>
                    <div class="fs-item"><span class="fs-icon">📊</span> dashboard.html</div>
                    <div class="fs-item"><span class="fs-icon">⚙️</span> app.js</div>
                    <div class="fs-item"><span class="fs-icon">📡</span> dashboard.js</div>
                    <div class="fs-item"><span class="fs-icon">🎨</span> styles.css</div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="cta-gradient"></div>
        <div class="cta-content">
            <h2>Start building smarter today.</h2>
            <p>Turn complex backend workflows into intelligent, automated systems.</p>
            @auth
                <a href="{{ route('dashboard') }}" class="btn-white btn-lg">Open Dashboard</a>
            @else
                <a href="{{ route('register') }}" class="btn-white btn-lg">Get Started — It's Free</a>
            @endauth
        </div>
    </section>

    <footer class="footer">
        <div class="footer-top">
            <div class="footer-brand">
                <div class="footer-logo">Backend<span>AI</span></div>
                <p>Building the future of intelligent backend development.</p>
                <div class="footer-socials">
                    <a href="#" aria-label="LinkedIn">in</a>
                    <a href="#" aria-label="GitHub">gh</a>
                    <a href="#" aria-label="Twitter">𝕏</a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Navigation</h4>
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#docs">Documentation</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Resources</h4>
                <ul>
                    <li><a href="#">Developer API</a></li>
                    <li><a href="#">GitHub Repository</a></li>
                    <li><a href="#">Support Community</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Social</h4>
                <ul>
                    <li><a href="#">LinkedIn</a></li>
                    <li><a href="#">GitHub</a></li>
                    <li><a href="#">Twitter / X</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <span>© 2026 BackendAI. All rights reserved.</span>
            <div class="footer-legal">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
            </div>
        </div>
    </footer>

    <script src="{{ asset('frontend/js/app.js') }}"></script>
</body>
</html>
