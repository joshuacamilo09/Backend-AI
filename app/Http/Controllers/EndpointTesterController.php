<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectEndpoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class EndpointTesterController extends Controller
{
    /**
     * Lista os endpoints guardados para um projeto.
     */
    public function endpoints(Project $project)
    {
        return response()->json([
            'data' => $project->endpoints()
                ->orderBy('path')
                ->orderBy('method')
                ->get(),
        ]);
    }

    /**
     * Executa um teste real contra um backend em execução.
     *
     * O utilizador precisa correr o backend gerado localmente
     * e informar a base_url, por exemplo:
     * http://127.0.0.1:9000
     */
    public function run(Request $request)
    {
        $validated = $request->validate([
            'endpoint_id' => ['required', 'exists:project_endpoints,id'],
            'base_url' => ['required', 'url'],
            'path' => ['required', 'string'],
            'method' => ['required', 'string'],
            'headers' => ['nullable', 'array'],
            'body' => ['nullable', 'array'],
        ]);

        $endpoint = ProjectEndpoint::findOrFail($validated['endpoint_id']);

        $method = strtoupper($validated['method']);
        $baseUrl = rtrim($validated['base_url'], '/');
        $path = '/' . ltrim($validated['path'], '/');

        $url = $baseUrl . $path;

        $headers = $validated['headers'] ?? [];
        $body = $validated['body'] ?? [];

        $startedAt = microtime(true);

        try {
            /**
             * Criamos o cliente HTTP com os headers enviados pelo utilizador.
             * Isto permite testar endpoints protegidos com Authorization Bearer token.
             */
            $client = Http::withHeaders($headers)
                ->acceptJson()
                ->timeout(30);

            $response = match ($method) {
                'GET' => $client->get($url),
                'POST' => $client->post($url, $body),
                'PUT' => $client->put($url, $body),
                'PATCH' => $client->patch($url, $body),
                'DELETE' => $client->delete($url, $body),
                default => throw new \InvalidArgumentException("Método HTTP não suportado: {$method}"),
            };

            $durationMs = round((microtime(true) - $startedAt) * 1000, 2);

            return response()->json([
                'ok' => true,
                'endpoint' => [
                    'id' => $endpoint->id,
                    'name' => $endpoint->name,
                    'method' => $endpoint->method,
                    'path' => $endpoint->path,
                ],
                'request' => [
                    'method' => $method,
                    'url' => $url,
                    'headers' => $headers,
                    'body' => $body,
                ],
                'response' => [
                    'status' => $response->status(),
                    'duration_ms' => $durationMs,
                    'headers' => $response->headers(),
                    'body' => $this->parseResponseBody($response->body()),
                ],
            ]);
        } catch (\Throwable $e) {
            $durationMs = round((microtime(true) - $startedAt) * 1000, 2);

            return response()->json([
                'ok' => false,
                'message' => 'Erro ao executar teste do endpoint.',
                'error' => $e->getMessage(),
                'duration_ms' => $durationMs,
            ], 500);
        }
    }

    /**
     * Tenta devolver JSON quando a resposta for JSON.
     * Se não for JSON, devolve texto normal.
     */
    protected function parseResponseBody(string $body): mixed
    {
        if (Str::isJson($body)) {
            return json_decode($body, true);
        }

        return $body;
    }
}
