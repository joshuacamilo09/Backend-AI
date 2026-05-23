-- BackendAI - PostgreSQL database schema
-- Ficheiro de referência para prova de autoria.
-- As migrations Laravel continuam a ser a fonte oficial no projecto.

CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    role VARCHAR(255) NOT NULL DEFAULT 'user',
    latitude NUMERIC(10,7) NULL,
    longitude NUMERIC(10,7) NULL,
    country VARCHAR(255) NULL,
    city VARCHAR(255) NULL,
    continent VARCHAR(255) NULL,
    user_type VARCHAR(255) NULL,
    experience_level VARCHAR(255) NULL,
    main_interest VARCHAR(255) NULL,
    last_login_at TIMESTAMP NULL,
    last_activity_at TIMESTAMP NULL,
    login_count INTEGER NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE projects (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NULL REFERENCES users(id) ON DELETE SET NULL,
    name VARCHAR(255) NOT NULL,
    framework VARCHAR(255) NOT NULL,
    template_key VARCHAR(255) NULL,
    description TEXT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE specifications (
    id BIGSERIAL PRIMARY KEY,
    project_id BIGINT NOT NULL REFERENCES projects(id) ON DELETE CASCADE,
    spec JSONB NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE generations (
    id BIGSERIAL PRIMARY KEY,
    project_id BIGINT NOT NULL REFERENCES projects(id) ON DELETE CASCADE,
    status VARCHAR(255) NOT NULL,
    output_path VARCHAR(255) NULL,
    duration_ms INTEGER NULL,
    zip_size_bytes BIGINT NULL,
    download_count INTEGER NOT NULL DEFAULT 0,
    avg_download_ms INTEGER NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE files (
    id BIGSERIAL PRIMARY KEY,
    generation_id BIGINT NOT NULL REFERENCES generations(id) ON DELETE CASCADE,
    path VARCHAR(255) NOT NULL,
    type VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE project_endpoints (
    id BIGSERIAL PRIMARY KEY,
    project_id BIGINT NOT NULL REFERENCES projects(id) ON DELETE CASCADE,
    method VARCHAR(255) NOT NULL,
    path VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    requires_auth BOOLEAN NOT NULL DEFAULT FALSE,
    sample_body JSONB NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE project_documentations (
    id BIGSERIAL PRIMARY KEY,
    project_id BIGINT NOT NULL REFERENCES projects(id) ON DELETE CASCADE,
    content TEXT NOT NULL,
    format VARCHAR(255) NOT NULL DEFAULT 'markdown',
    download_count INTEGER NOT NULL DEFAULT 0,
    duration_ms INTEGER NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Tabelas default Laravel
CREATE TABLE password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
);

CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload TEXT NOT NULL,
    last_activity INTEGER NOT NULL
);

CREATE INDEX sessions_user_id_index ON sessions(user_id);
CREATE INDEX sessions_last_activity_index ON sessions(last_activity);

CREATE TABLE cache (
    key VARCHAR(255) PRIMARY KEY,
    value TEXT NOT NULL,
    expiration INTEGER NOT NULL
);

CREATE TABLE cache_locks (
    key VARCHAR(255) PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration INTEGER NOT NULL
);

CREATE TABLE jobs (
    id BIGSERIAL PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload TEXT NOT NULL,
    attempts SMALLINT NOT NULL,
    reserved_at INTEGER NULL,
    available_at INTEGER NOT NULL,
    created_at INTEGER NOT NULL
);

CREATE INDEX jobs_queue_reserved_available_index ON jobs(queue, reserved_at, available_at);

CREATE TABLE job_batches (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    total_jobs INTEGER NOT NULL,
    pending_jobs INTEGER NOT NULL,
    failed_jobs INTEGER NOT NULL,
    failed_job_ids TEXT NOT NULL,
    options TEXT NULL,
    cancelled_at INTEGER NULL,
    created_at INTEGER NOT NULL,
    finished_at INTEGER NULL
);

CREATE TABLE failed_jobs (
    id BIGSERIAL PRIMARY KEY,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload TEXT NOT NULL,
    exception TEXT NOT NULL,
    failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Inserts mínimos de demonstração.
-- Nota: no projecto real, usa o seeder FinalDemoDataSeeder porque ele usa Hash::make().
INSERT INTO users (name, email, password, role, country, city, continent, latitude, longitude, user_type, experience_level, main_interest, last_login_at, last_activity_at, login_count, created_at, updated_at)
VALUES
('BackendAI Admin', 'admin@backendai.test', '<bcrypt-password>', 'admin', 'Portugal', 'Lisboa', 'Europe', 38.7223000, -9.1393000, 'teacher', 'advanced', 'ai', NOW(), NOW(), 15, NOW(), NOW()),
('Ana Silva', 'ana@test.com', '<bcrypt-password>', 'user', 'Portugal', 'Porto', 'Europe', 41.1579000, -8.6291000, 'student', 'beginner', 'backend', NOW(), NOW(), 2, NOW(), NOW());

INSERT INTO projects (user_id, name, framework, template_key, description, created_at, updated_at)
VALUES
(1, 'Library Management API - BackendAI Admin #1', 'laravel', 'library', 'Sistema de gestão de livraria com livros, autores, categorias e empréstimos.', NOW(), NOW());

INSERT INTO specifications (project_id, spec, created_at, updated_at)
VALUES
(1, '{"project_name":"Library Management API - BackendAI Admin #1","framework":"laravel","auth":{"enabled":true,"type":"sanctum"},"entities":[{"name":"Book","table":"books","fields":[{"name":"name","type":"string","required":true},{"name":"description","type":"text","required":false}]}]}'::jsonb, NOW(), NOW());

INSERT INTO generations (project_id, status, output_path, duration_ms, zip_size_bytes, download_count, avg_download_ms, created_at, updated_at)
VALUES
(1, 'completed', '/fake/generated/library-1-0.zip', 5300, 2500000, 3, 250, NOW(), NOW());

INSERT INTO project_endpoints (project_id, method, path, name, description, requires_auth, sample_body, created_at, updated_at)
VALUES
(1, 'GET', '/api/books', 'List Book', 'List Book endpoint.', TRUE, NULL, NOW(), NOW()),
(1, 'POST', '/api/books', 'Create Book', 'Create Book endpoint.', TRUE, '{"name":"Example Book","description":"Example description"}'::jsonb, NOW(), NOW());

INSERT INTO project_documentations (project_id, content, format, download_count, duration_ms, created_at, updated_at)
VALUES
(1, '# Library Management API\n\nDocumentação automática de teste.', 'markdown', 1, 1000, NOW(), NOW());

-- Queries úteis para teste/analytics
-- Total de utilizadores
SELECT COUNT(*) AS total_users FROM users;
-- Total de projectos
SELECT COUNT(*) AS total_projects FROM projects;
-- Taxa de sucesso das gerações
SELECT ROUND(100.0 * SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) / NULLIF(COUNT(*), 0), 1) AS success_rate FROM generations;
-- Top países
SELECT country AS label, COUNT(*) AS total FROM users WHERE country IS NOT NULL GROUP BY country ORDER BY total DESC;
-- Top templates
SELECT template_key AS label, COUNT(*) AS total FROM projects WHERE template_key IS NOT NULL GROUP BY template_key ORDER BY total DESC;
