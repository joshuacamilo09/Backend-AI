# Backend AI: Laravel Backend Generator

Describe a system in plain language, for example *"a library where members borrow books, with login"*, and get back a **ready-to-run Laravel backend** as a ZIP: migrations, models with relationships, form requests, services, CRUD controllers, API routes and optional authentication.

The platform also documents the generated API, lets you test its endpoints from the browser, and gives admins an analytics dashboard of how it's being used.

> University team project (IADE – Universidade Europeia, 2026). Design documents, user flows, REST API and database schema are on the [`Documentos`](https://github.com/joshuacamilo09/Backend-AI/tree/Documentos) branch.

---

## How generation works

```
 "A library where members borrow books..."
                 │
                 ▼
 ┌───────────────────────────────┐   prompt with strict JSON contract, temperature 0.2
 │ AIInterpreter                 │ ─────────────────────────────────────────────▶ Gemini
 │ GeminiClient: retries,        │ ◀───────────────── JSON specification ────────
 │ exponential backoff, fallback │
 └───────────────┬───────────────┘
                 ▼
 Specification (stored)  { project_name, auth, entities[ fields, relations ] }
                 ▼
 ┌───────────────────────────────────────────────────────────────────────────┐
 │ LaravelProjectGenerator                                                  │
 │  1. composer create-project laravel/laravel  (a real, fresh project)     │
 │  2. docs/specification.json + README                                     │
 │  3. Models + relationships   (hasMany / belongsTo)                       │
 │  4. MigrationGenerator  5. ServiceGenerator  6. RequestGenerator         │
 │  7. ControllerGenerator 8. AuthGenerator (Sanctum)  9. RouteGenerator    │
 └───────────────┬───────────────────────────────────────────────────────────┘
                 ▼
 ZipExporter → download   ·   EndpointExtractor → endpoints for the tester
```

The model is used only for what it's good at, **turning free text into a structured spec**. All code is produced by **deterministic generators** from that spec, so the output is predictable, reviewable and always follows the same conventions.

The Gemini client retries temporary errors (`429`, `5xx`) with **exponential backoff** and can fall back to a second model, so a busy model doesn't break a generation.

## Features

- **Generate backend**: from a description or from a template, with optional auth
- **Projects & generations**: history of every project, its spec and generation status; download any generation as a ZIP
- **Documentation**: auto-generated API documentation per project, exportable as **PDF**
- **API tester**: lists the generated endpoints and sends real requests to a running instance (including Bearer-protected routes)
- **Admin analytics**: users and generations per day, framework and template usage, peak activity hour, users by country / city / continent
- **Accounts**: registration, login, email verification, password reset and profile (Laravel Breeze); admin-only routes behind an `AdminMiddleware`

## Code structure

```
app/
├── Services/
│   ├── AI/AIInterpreter.php              description → JSON spec (prompt + parsing)
│   ├── Gemini/GeminiClient.php           HTTP client: retries, backoff, fallback model
│   ├── Generation/
│   │   ├── LaravelProjectGenerator.php   orchestrates the generation pipeline
│   │   ├── Laravel/*Generator.php        migrations, controllers, services, requests, routes, auth
│   │   ├── StoreGenerationService.php    persists project, spec and generation
│   │   └── ZipExporter.php
│   ├── Documentation/                    API documentation generator
│   ├── Testing/EndpointExtractorService.php
│   └── Analytics/                        admin and user metrics
├── Http/Controllers/                     thin controllers per feature
├── Http/Middleware/AdminMiddleware.php
└── Models/                               Project, Specification, Generation, File, ProjectEndpoint, ProjectDocumentation
database/migrations/                      schema of the platform
public/frontend/                          page-level CSS and JavaScript
resources/views/                          Blade views
```

## Running locally

Requirements: PHP 8.2+, Composer, Node.js, PostgreSQL and a [Gemini API key](https://aistudio.google.com/app/apikey).

```bash
cp .env.example .env          # DB credentials and GEMINI_API_KEY
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed --class=FinalDemoDataSeeder   # optional demo data
npm install && npm run build
php artisan serve             # http://localhost:8000
```

Generation runs `composer create-project`, so Composer must be available to the PHP process.

## Tech stack

PHP 8.2 · Laravel 12 · PostgreSQL · Google Gemini API · Laravel Breeze · Sanctum (in generated projects) · DomPDF · Blade · JavaScript · Vite
