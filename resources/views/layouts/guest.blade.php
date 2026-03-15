<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BackendAI') }}</title>

        <link rel="shortcut icon" href="{{ asset('frontend/assets/icons/network.png') }}" type="image/x-icon">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --bg: #050816;
                --bg-secondary: #09122a;
                --panel: rgba(12, 20, 38, 0.88);
                --panel-border: rgba(96, 165, 250, 0.14);
                --text: #f8fafc;
                --text-muted: #94a3b8;
                --primary: #3b82f6;
                --primary-hover: #2563eb;
                --glow: rgba(59, 130, 246, 0.22);
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                font-family: "DM Sans", sans-serif;
                color: var(--text);
                background:
                    radial-gradient(circle at top left, rgba(37, 99, 235, 0.20), transparent 28%),
                    radial-gradient(circle at top right, rgba(59, 130, 246, 0.16), transparent 22%),
                    linear-gradient(180deg, #050816 0%, #08112a 52%, #050816 100%);
                min-height: 100vh;
            }

            .auth-shell {
                min-height: 100vh;
                display: grid;
                grid-template-columns: 1.05fr 0.95fr;
            }

            .auth-brand {
                position: relative;
                overflow: hidden;
                padding: 40px 48px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                border-right: 1px solid rgba(148, 163, 184, 0.08);
                background:
                    radial-gradient(circle at 20% 20%, rgba(59, 130, 246, 0.18), transparent 25%),
                    radial-gradient(circle at 80% 30%, rgba(29, 78, 216, 0.16), transparent 22%),
                    linear-gradient(180deg, rgba(7, 14, 31, 0.96) 0%, rgba(6, 12, 28, 0.92) 100%);
            }

            .auth-brand::after {
                content: "";
                position: absolute;
                inset: auto -120px -120px auto;
                width: 320px;
                height: 320px;
                background: var(--glow);
                filter: blur(80px);
                pointer-events: none;
            }

            .auth-logo {
                display: inline-flex;
                align-items: center;
                gap: 12px;
                text-decoration: none;
                color: var(--text);
                font-weight: 700;
                font-size: 1.1rem;
                letter-spacing: -0.02em;
            }

            .auth-logo img {
                width: 22px;
                height: 22px;
                object-fit: contain;
            }

            .auth-logo span {
                color: #60a5fa;
            }

            .auth-copy {
                max-width: 560px;
                position: relative;
                z-index: 1;
            }

            .auth-copy h1 {
                margin: 0 0 16px;
                font-size: clamp(2.5rem, 4vw, 4.5rem);
                line-height: 0.98;
                letter-spacing: -0.05em;
                font-weight: 800;
            }

            .auth-copy p {
                margin: 0;
                color: var(--text-muted);
                font-size: 1.02rem;
                line-height: 1.7;
                max-width: 520px;
            }

            .auth-points {
                display: grid;
                gap: 14px;
                max-width: 460px;
                position: relative;
                z-index: 1;
            }

            .auth-point {
                display: flex;
                align-items: center;
                gap: 12px;
                color: #cbd5e1;
                font-size: 0.95rem;
            }

            .auth-point-dot {
                width: 10px;
                height: 10px;
                border-radius: 999px;
                background: #3b82f6;
                box-shadow: 0 0 0 6px rgba(59, 130, 246, 0.12);
                flex-shrink: 0;
            }

            .auth-panel-wrap {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 36px 24px;
            }

            .auth-panel {
                width: 100%;
                max-width: 460px;
                background: var(--panel);
                border: 1px solid var(--panel-border);
                border-radius: 24px;
                padding: 28px;
                backdrop-filter: blur(18px);
                box-shadow:
                    0 10px 40px rgba(2, 8, 23, 0.35),
                    inset 0 1px 0 rgba(255, 255, 255, 0.03);
            }

            .auth-panel-header {
                margin-bottom: 22px;
            }

            .auth-panel-kicker {
                display: inline-block;
                padding: 6px 10px;
                border-radius: 999px;
                background: rgba(59, 130, 246, 0.10);
                color: #93c5fd;
                font-size: 0.78rem;
                font-weight: 600;
                letter-spacing: 0.01em;
                margin-bottom: 14px;
            }

            .auth-panel-title {
                margin: 0;
                font-size: 1.8rem;
                font-weight: 700;
                letter-spacing: -0.03em;
                color: var(--text);
            }

            .auth-panel-subtitle {
                margin: 10px 0 0;
                color: var(--text-muted);
                line-height: 1.6;
                font-size: 0.95rem;
            }

            @media (max-width: 980px) {
                .auth-shell {
                    grid-template-columns: 1fr;
                }

                .auth-brand {
                    padding: 28px 24px 20px;
                    gap: 36px;
                    border-right: 0;
                    border-bottom: 1px solid rgba(148, 163, 184, 0.08);
                }

                .auth-copy h1 {
                    font-size: 2.4rem;
                }

                .auth-points {
                    gap: 10px;
                }
            }

            @media (max-width: 640px) {
                .auth-brand {
                    padding: 24px 18px 18px;
                }

                .auth-panel-wrap {
                    padding: 18px;
                }

                .auth-panel {
                    padding: 20px;
                    border-radius: 18px;
                }

                .auth-copy h1 {
                    font-size: 2rem;
                }
            }
        </style>
    </head>
    <body>
        <div class="auth-shell">
            <section class="auth-brand">
                <a href="{{ url('/') }}" class="auth-logo">
                    <img src="{{ asset('frontend/assets/icons/network.png') }}" alt="BackendAI logo">
                    <span>Backend</span>AI
                </a>

                <div class="auth-copy">
                    <h1>Build production-ready backends with AI.</h1>
                    <p>
                        BackendAI transforma descrições em linguagem natural em backends estruturados,
                        com geração automática, histórico de projectos e downloads prontos para desenvolvimento.
                    </p>
                </div>

                <div class="auth-points">
                    <div class="auth-point">
                        <span class="auth-point-dot"></span>
                        <span>Geração automática de projectos Laravel</span>
                    </div>
                    <div class="auth-point">
                        <span class="auth-point-dot"></span>
                        <span>Histórico privado por utilizador autenticado</span>
                    </div>
                    <div class="auth-point">
                        <span class="auth-point-dot"></span>
                        <span>Download directo de backends em ZIP</span>
                    </div>
                </div>
            </section>

            <section class="auth-panel-wrap">
                <div class="auth-panel">
                    <div class="auth-panel-header">
                        <div class="auth-panel-kicker">BackendAI Platform</div>
                        <h2 class="auth-panel-title">Continue</h2>
                        <p class="auth-panel-subtitle">
                            Acede à tua conta para gerar, gerir e descarregar os teus backends.
                        </p>
                    </div>

                    {{ $slot }}
                </div>
            </section>
        </div>
    </body>
</html>
