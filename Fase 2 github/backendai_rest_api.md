# BackendAI — Documentação REST do Backend

## Visão geral

O BackendAI é uma plataforma Laravel que permite ao utilizador descrever um backend em linguagem natural, gerar uma specification com IA, criar o projecto Laravel, guardar metadados na base de dados, disponibilizar download do ZIP, testar endpoints internos e gerar documentação do projecto.

A plataforma usa autenticação baseada em sessão web do Laravel Breeze. Por isso, os endpoints internos em `/app-api/*` são chamados pelo frontend autenticado com `credentials: "same-origin"`.

## Base URL local

```txt
http://127.0.0.1:8000
```

## Autenticação

A maioria das rotas está protegida por `auth` e, nas páginas, também por `verified`.

Fluxo principal:

1. Utilizador faz login em `/login`.
2. Laravel cria sessão autenticada.
3. O frontend chama `/app-api/*` usando cookies da sessão.
4. O backend valida `auth()->user()`.

---

# Endpoints de páginas Web

| Método | URL | Nome | Descrição | Middleware |
|---|---|---|---|---|
| GET | `/` | — | Landing page pública | público |
| GET | `/dashboard` | `dashboard` | Dashboard do utilizador | auth, verified |
| GET | `/generate-backend` | `generate-backend` | Página de geração de backend | auth, verified |
| GET | `/projects` | `projects` | Página de projectos | auth, verified |
| GET | `/templates` | `templates` | Página de templates | auth, verified |
| GET | `/documentation` | `documentation` | Página de documentação | auth, verified |
| GET | `/api-tester` | `api-tester` | Página do API Tester interno | auth, verified |
| GET | `/settings` | `settings` | Página de definições | auth, verified |
| GET | `/analytics` | `analytics` | Dashboard avançado de analytics | auth, verified, admin |

---

# Endpoints internos da plataforma `/app-api`

## 1. Gerar backend

### `POST /app-api/generate-backend`

Gera um backend a partir da descrição do utilizador.

### Controller

`BackendGenerationController@store`

### Body

```json
{
  "description": "I need a library management API with books, authors, categories and loans."
}
```

### Validação

```txt
description: required|string|min:10
```

### Processo interno

1. Verifica se existe utilizador autenticado.
2. Valida a descrição.
3. Chama `AIInterpreter` para transformar texto em specification.
4. Chama `StoreGenerationService` para guardar projecto, specification e generation.
5. Chama `EndpointExtractorService` para guardar endpoints esperados.
6. Chama `LaravelProjectGenerator` para gerar ficheiros do backend.
7. Chama `ZipExporter` para criar ZIP.
8. Actualiza a generation para `completed`.
9. Devolve metadados e URL de download.

### Resposta 201

```json
{
  "message": "Backend gerado com sucesso.",
  "project": {
    "id": 1,
    "name": "library-management-api",
    "framework": "laravel"
  },
  "generation": {
    "id": 1,
    "project_id": 1,
    "status": "completed",
    "download_url": "http://127.0.0.1:8000/app-api/generations/1/download"
  }
}
```

### Erros

| Código | Motivo |
|---|---|
| 401 | Utilizador não autenticado |
| 422 | Descrição inválida |
| 500 | Erro na IA, geração, ZIP ou filesystem |

---

## 2. Listar projectos

### `GET /app-api/projects`

Lista apenas os projectos pertencentes ao utilizador autenticado.

### Controller

`ProjectController@index`

### Resposta 200

```json
{
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "name": "Library Management API",
      "framework": "laravel",
      "template_key": "library",
      "description": "Sistema de gestão de livraria...",
      "specification": {},
      "generations": []
    }
  ]
}
```

---

## 3. Detalhe de projecto

### `GET /app-api/projects/{project}`

Mostra um projecto específico do próprio utilizador.

### Controller

`ProjectController@show`

### Segurança

O controller verifica:

```php
if ($project->user_id !== $request->user()->id) abort(403);
```

### Respostas

| Código | Motivo |
|---|---|
| 200 | Projecto encontrado |
| 403 | Projecto pertence a outro utilizador |
| 404 | Projecto não existe |

---

## 4. Listar gerações

### `GET /app-api/generations`

Lista gerações dos projectos do utilizador autenticado.

### Controller

`GenerationController@index`

### Resposta 200

```json
{
  "data": [
    {
      "id": 1,
      "project_id": 1,
      "status": "completed",
      "output_path": "/storage/generated/project.zip",
      "download_url": "http://127.0.0.1:8000/app-api/generations/1/download"
    }
  ]
}
```

---

## 5. Detalhe de geração

### `GET /app-api/generations/{generation}`

Mostra detalhe de uma geração do próprio utilizador.

### Controller

`GenerationController@show`

### Segurança

A geração só é visível se o projecto associado pertencer ao utilizador autenticado.

---

## 6. Download de ZIP gerado

### `GET /app-api/generations/{generation}/download`

Descarrega o ZIP gerado.

### Controller

`DownloadGeneratedBackendController::__invoke`

### Processo interno

1. Carrega o projecto da geração.
2. Verifica se o projecto pertence ao utilizador autenticado.
3. Verifica se o ficheiro ZIP existe.
4. Incrementa `download_count`.
5. Actualiza `avg_download_ms`.
6. Retorna `response()->download()`.

### Respostas

| Código | Motivo |
|---|---|
| 200 | Download iniciado |
| 403 | ZIP pertence a outro utilizador |
| 404 | ZIP não existe |

---

## 7. Listar endpoints de um projecto

### `GET /app-api/projects/{project}/endpoints`

Lista os endpoints guardados para um projecto gerado.

### Controller

`EndpointTesterController@endpoints`

### Resposta 200

```json
{
  "data": [
    {
      "id": 1,
      "project_id": 1,
      "method": "GET",
      "path": "/api/books",
      "name": "List Book",
      "description": "List Book endpoint.",
      "requires_auth": true,
      "sample_body": null
    }
  ]
}
```

---

## 8. Executar teste de endpoint

### `POST /app-api/endpoint-tests/run`

Executa uma request real contra o backend gerado que está a correr localmente ou noutro URL.

### Controller

`EndpointTesterController@run`

### Body

```json
{
  "endpoint_id": 1,
  "base_url": "http://127.0.0.1:9000",
  "path": "/api/books",
  "method": "GET",
  "headers": {
    "Authorization": "Bearer TOKEN"
  },
  "body": {}
}
```

### Validação

```txt
endpoint_id: required|exists:project_endpoints,id
base_url: required|url
path: required|string
method: required|string
headers: nullable|array
body: nullable|array
```

### Resposta 200

```json
{
  "ok": true,
  "endpoint": {
    "id": 1,
    "name": "List Book",
    "method": "GET",
    "path": "/api/books"
  },
  "request": {
    "method": "GET",
    "url": "http://127.0.0.1:9000/api/books"
  },
  "response": {
    "status": 200,
    "duration_ms": 120.5,
    "headers": {},
    "body": []
  }
}
```

### Erro 500

```json
{
  "ok": false,
  "message": "Erro ao executar teste do endpoint.",
  "error": "Connection refused",
  "duration_ms": 30.2
}
```

---

## 9. Ver documentação de projecto

### `GET /app-api/projects/{project}/documentation`

Mostra documentação já gerada para um projecto.

### Controller

`ProjectDocumentationController@show`

### Resposta 200

```json
{
  "data": {
    "id": 1,
    "project_id": 1,
    "content": "# Project Documentation...",
    "format": "markdown",
    "download_count": 0,
    "duration_ms": 900
  }
}
```

### Erro 404

```json
{
  "message": "Documentation has not been generated yet.",
  "data": null
}
```

---

## 10. Gerar documentação

### `POST /app-api/projects/{project}/documentation/generate`

Gera ou actualiza documentação em Markdown para o projecto.

### Controller

`ProjectDocumentationController@generate`

### Processo interno

1. Mede o tempo de início.
2. Chama `ProjectDocumentationGenerator`.
3. Calcula `duration_ms`.
4. Cria ou actualiza `project_documentations`.
5. Retorna a documentação gerada.

### Resposta 200

```json
{
  "message": "Documentation generated successfully.",
  "data": {
    "id": 1,
    "project_id": 1,
    "content": "# Documentation",
    "format": "markdown",
    "duration_ms": 1200
  }
}
```

---

## 11. Download da documentação em PDF

### `GET /app-api/projects/{project}/documentation/download-pdf`

Converte Markdown para HTML e gera PDF com DomPDF.

### Controller

`ProjectDocumentationController@downloadPdf`

### Processo interno

1. Verifica se existe documentação.
2. Converte Markdown para HTML com CommonMark.
3. Renderiza a view `pdf.project-documentation`.
4. Gera PDF A4 com DomPDF.
5. Incrementa `download_count`.
6. Faz download do ficheiro.

### Respostas

| Código | Motivo |
|---|---|
| 200 | PDF descarregado |
| 404 | Documentação ainda não foi gerada |

---

## 12. Analytics admin

### `GET /app-api/admin/analytics`

Retorna métricas globais da plataforma. Apenas admin.

### Controller

`AnalyticsController@admin`

### Resposta exemplo

```json
{
  "data": {
    "totals": {
      "users": 16,
      "projects": 50,
      "generations": 120
    },
    "rates": {
      "success_rate": 82.5,
      "error_rate": 17.5
    },
    "geo": {
      "countries": [],
      "cities": [],
      "continents": []
    }
  }
}
```

---

# Endpoint de debug em `routes/api.php`

## `POST /api/ai/parse`

Testa apenas o parser da IA, sem guardar nada na base de dados.

### Body

```json
{
  "description": "I need an API for products and orders."
}
```

### Resposta 200

```json
{
  "project_name": "products-and-orders-api",
  "framework": "laravel",
  "entities": []
}
```

---

# Auth Laravel Breeze

| Método | URL | Descrição |
|---|---|---|
| GET | `/register` | Formulário de registo |
| POST | `/register` | Cria utilizador |
| GET | `/login` | Formulário de login |
| POST | `/login` | Autentica utilizador |
| POST | `/logout` | Termina sessão |
| GET | `/forgot-password` | Formulário para pedir reset |
| POST | `/forgot-password` | Envia link de reset |
| GET | `/reset-password/{token}` | Formulário de nova password |
| POST | `/reset-password` | Guarda nova password |
| GET | `/verify-email` | Pedido de verificação de email |
| POST | `/email/verification-notification` | Reenvia verificação |
| GET | `/confirm-password` | Confirma password |
| POST | `/confirm-password` | Valida confirmação |
| PUT | `/password` | Actualiza password |

---

# Modelo de dados resumido

```txt
users 1 ─── * projects
projects 1 ─── 1 specifications
projects 1 ─── * generations
projects 1 ─── * project_endpoints
projects 1 ─── 1 project_documentations
generations 1 ─── * files
```

## Tabelas principais

- `users`: utilizadores, role, localização, dados de analytics.
- `projects`: projectos criados por utilizadores.
- `specifications`: JSON da specification gerada pela IA.
- `generations`: histórico de gerações e métricas de performance.
- `files`: ficheiros gerados por generation.
- `project_endpoints`: endpoints detectados/gerados para o API Tester.
- `project_documentations`: documentação Markdown/PDF dos projectos.

---

# Comandos úteis

```bash
php artisan migrate
php artisan db:seed --class=FinalDemoDataSeeder
php artisan serve
```

Login demo:

```txt
Email: admin@backendai.test
Password: password
```
