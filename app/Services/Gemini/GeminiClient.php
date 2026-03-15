<?php

namespace App\Services\Gemini;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class GeminiClient
{
    /**
     * Envia um prompt para o Gemini API e devolve a resposta raw.
     *
     * Inclui:
     * - retry automático para erros temporários (503, 429, etc.)
     * - exponential backoff
     * - fallback opcional para um modelo alternativo
     */
    public function generate(string $prompt, array $generationConfig = []): array
    {
        $apiKey = config('services.gemini.api_key');
        $baseUrl = config('services.gemini.base_url');
        $primaryModel = config('services.gemini.model');
        $fallbackModel = config('services.gemini.fallback_model');
        $timeout = (int) config('services.gemini.timeout', 60);
        $maxRetries = (int) config('services.gemini.max_retries', 4);

        if (empty($apiKey)) {
            throw new \RuntimeException('GEMINI_API_KEY não está configurada.');
        }

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                    ],
                ],
            ],
        ];

        // Permite configurar temperatura, topP, etc.
        if (!empty($generationConfig)) {
            $payload['generationConfig'] = $generationConfig;
        }

        // 1) Tentar com o modelo principal
        try {
            return $this->requestWithRetry(
                model: $primaryModel,
                baseUrl: $baseUrl,
                apiKey: $apiKey,
                payload: $payload,
                timeout: $timeout,
                maxRetries: $maxRetries
            );
        } catch (\Throwable $e) {
            // 2) Se existir fallback, tenta uma segunda vez noutro modelo
            if (!empty($fallbackModel) && $fallbackModel !== $primaryModel) {
                try {
                    return $this->requestWithRetry(
                        model: $fallbackModel,
                        baseUrl: $baseUrl,
                        apiKey: $apiKey,
                        payload: $payload,
                        timeout: $timeout,
                        maxRetries: $maxRetries
                    );
                } catch (\Throwable $fallbackError) {
                    throw new \RuntimeException(
                        "Gemini falhou no modelo principal [{$primaryModel}] e no fallback [{$fallbackModel}]. " .
                        "Erro final: " . $fallbackError->getMessage()
                    );
                }
            }

            throw $e;
        }
    }

    /**
     * Faz request ao Gemini com retry e backoff.
     */
    protected function requestWithRetry(
        string $model,
        string $baseUrl,
        string $apiKey,
        array $payload,
        int $timeout,
        int $maxRetries
    ): array {
        $url = "{$baseUrl}/models/{$model}:generateContent?key={$apiKey}";

        $attempt = 0;
        $delaysInMs = [1000, 2000, 4000, 8000, 12000];

        start:

        try {
            $response = Http::timeout($timeout)
                ->acceptJson()
                ->asJson()
                ->post($url, $payload);

            if ($response->successful()) {
                return $response->json();
            }

            // Erros temporários que merecem retry
            if ($this->shouldRetryStatus($response->status()) && $attempt < $maxRetries) {
                usleep(($delaysInMs[$attempt] ?? 12000) * 1000);
                $attempt++;
                goto start;
            }

            throw new \RuntimeException(
                "Erro ao comunicar com Gemini API [modelo: {$model}] [status: {$response->status()}]: " .
                $response->body()
            );
        } catch (ConnectionException $e) {
            if ($attempt < $maxRetries) {
                usleep(($delaysInMs[$attempt] ?? 12000) * 1000);
                $attempt++;
                goto start;
            }

            throw new \RuntimeException(
                "Falha de ligação ao Gemini API [modelo: {$model}]: " . $e->getMessage()
            );
        } catch (RequestException $e) {
            if ($attempt < $maxRetries) {
                usleep(($delaysInMs[$attempt] ?? 12000) * 1000);
                $attempt++;
                goto start;
            }

            throw new \RuntimeException(
                "Erro de request ao Gemini API [modelo: {$model}]: " . $e->getMessage()
            );
        }
    }

    /**
     * Decide se um status HTTP merece retry.
     */
    protected function shouldRetryStatus(int $status): bool
    {
        return in_array($status, [
            408, // Request Timeout
            409, // Conflict / transient
            425, // Too Early
            429, // Too Many Requests
            500, // Server error
            502, // Bad gateway
            503, // Service unavailable
            504, // Gateway timeout
        ], true);
    }
}
