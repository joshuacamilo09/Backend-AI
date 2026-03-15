<?php

namespace App\Services\AI;

use App\Services\Gemini\GeminiClient;

class AIInterpreter
{
    public function __construct(
        protected GeminiClient $geminiClient
    ) {}

    public function parse(string $description): array
    {
        $prompt = $this->buildPrompt($description);

        $response = $this->geminiClient->generate($prompt, [
            'temperature' => 0.2,
        ]);

        $text = $response['candidates'][0]['content']['parts'][0]['text'] ?? null;

        if (!$text) {
            throw new \RuntimeException('Resposta inválida do Gemini: texto não encontrado.');
        }

        $text = trim($text);
        $text = preg_replace('/^```json\s*/', '', $text);
        $text = preg_replace('/^```\s*/', '', $text);
        $text = preg_replace('/\s*```$/', '', $text);

        $decoded = json_decode($text, true);

        if (!is_array($decoded)) {
            throw new \RuntimeException('Gemini devolveu JSON inválido: ' . $text);
        }

        return $decoded;
    }

    protected function buildPrompt(string $description): string
    {
        return <<<PROMPT
You are a backend software architect and engineer expert.

Your job is to read the user's system description and convert it into a structured JSON specification
for a Laravel backend generator.

Return ONLY valid JSON.
Do not include explanations.
Do not use markdown.
Do not wrap in triple backticks.

Expected JSON format:

{
  "project_name": "string",
  "framework": "laravel",
  "auth": {
    "enabled": true,
    "type": "jwt"
  },
  "entities": [
    {
      "name": "User",
      "table": "users",
      "fields": [
        {
          "name": "name",
          "type": "string",
          "required": true,
          "unique": false
        }
      ]
    }
  ],
  "relations": [
    {
      "type": "one-to-many",
      "from": "User",
      "to": "Task",
      "foreign_key": "user_id"
    }
  ],
  "features": [
    "crud",
    "validation",
    "documentation"
  ]
}

Rules:
- framework must always be "laravel"
- infer entities, fields and relations
- include authentication if the description suggests user accounts or login
- include useful default fields when obvious
- use Laravel-friendly table names
- keep names in English
- return only JSON

User description:
{$description}
PROMPT;
    }
}
