<?php

namespace App\Services\Testing;

use App\Models\Project;
use Illuminate\Support\Str;

class EndpointExtractorService
{
    /**
     * Extrai endpoints a partir da specification gerada pela IA.
     *
     * Neste momento, assumimos que cada entidade gera um CRUD REST.
     * Exemplo:
     * Entity Book -> /api/books
     */
    public function extract(Project $project, array $spec): void
    {
        $entities = $spec['entities'] ?? [];
        $authEnabled = ($spec['auth']['enabled'] ?? false) === true;

        foreach ($entities as $entity) {
            $name = $entity['name'] ?? null;

            if (!$name || $name === 'User') {
                continue;
            }

            // O RouteGenerator usa resource plural em kebab.
            // Exemplo: TaskComment -> task-comments
            $resource = Str::kebab(Str::pluralStudly($name));

            $basePath = "/api/{$resource}";

            $sampleBody = $this->buildSampleBody($entity['fields'] ?? []);

            $project->endpoints()->createMany([
                [
                    'method' => 'GET',
                    'path' => $basePath,
                    'name' => "List {$resource}",
                    'description' => "Lista todos os registos de {$name}.",
                    'requires_auth' => $authEnabled,
                    'sample_body' => null,
                ],
                [
                    'method' => 'POST',
                    'path' => $basePath,
                    'name' => "Create {$name}",
                    'description' => "Cria um novo registo de {$name}.",
                    'requires_auth' => $authEnabled,
                    'sample_body' => $sampleBody,
                ],
                [
                    'method' => 'GET',
                    'path' => "{$basePath}/{id}",
                    'name' => "Show {$name}",
                    'description' => "Obtém um registo específico de {$name}.",
                    'requires_auth' => $authEnabled,
                    'sample_body' => null,
                ],
                [
                    'method' => 'PUT',
                    'path' => "{$basePath}/{id}",
                    'name' => "Update {$name}",
                    'description' => "Atualiza um registo de {$name}.",
                    'requires_auth' => $authEnabled,
                    'sample_body' => $sampleBody,
                ],
                [
                    'method' => 'DELETE',
                    'path' => "{$basePath}/{id}",
                    'name' => "Delete {$name}",
                    'description' => "Remove um registo de {$name}.",
                    'requires_auth' => $authEnabled,
                    'sample_body' => null,
                ],
            ]);
        }

        if ($authEnabled) {
            $this->createAuthEndpoints($project);
        }
    }

    /**
     * Cria exemplos simples de body a partir dos campos da entidade.
     */
    protected function buildSampleBody(array $fields): array
    {
        $body = [];

        foreach ($fields as $field) {
            $name = $field['name'] ?? null;
            $type = $field['type'] ?? 'string';

            if (!$name || in_array($name, ['id', 'created_at', 'updated_at'])) {
                continue;
            }

            $body[$name] = match ($type) {
                'email' => 'user@example.com',
                'integer' => 1,
                'boolean' => true,
                'decimal', 'float' => 99.99,
                'date' => '2026-01-01',
                'datetime' => '2026-01-01 10:00:00',
                'text' => 'Example text',
                default => "Example {$name}",
            };
        }

        return $body;
    }

    /**
     * Adiciona endpoints de autenticação quando o backend gerado tiver auth.
     */
    protected function createAuthEndpoints(Project $project): void
    {
        $project->endpoints()->createMany([
            [
                'method' => 'POST',
                'path' => '/api/register',
                'name' => 'Register',
                'description' => 'Regista um novo utilizador.',
                'requires_auth' => false,
                'sample_body' => [
                    'name' => 'Test User',
                    'email' => 'test@example.com',
                    'password' => 'password',
                    'password_confirmation' => 'password',
                ],
            ],
            [
                'method' => 'POST',
                'path' => '/api/login',
                'name' => 'Login',
                'description' => 'Autentica o utilizador e devolve token.',
                'requires_auth' => false,
                'sample_body' => [
                    'email' => 'test@example.com',
                    'password' => 'password',
                ],
            ],
            [
                'method' => 'GET',
                'path' => '/api/me',
                'name' => 'Authenticated User',
                'description' => 'Obtém dados do utilizador autenticado.',
                'requires_auth' => true,
                'sample_body' => null,
            ],
            [
                'method' => 'POST',
                'path' => '/api/logout',
                'name' => 'Logout',
                'description' => 'Termina a sessão/token do utilizador.',
                'requires_auth' => true,
                'sample_body' => null,
            ],
        ]);
    }
}
