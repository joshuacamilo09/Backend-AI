<?php

namespace App\Services\Generation\Laravel;

use Illuminate\Support\Facades\File;

class RequestGenerator
{
    /**
     * Gera Form Requests de validação:
     * - StoreXRequest
     * - UpdateXRequest
     */
    public function generate(string $projectPath, array $spec): void
    {
        $entities = $spec['entities'] ?? [];

        File::ensureDirectoryExists($projectPath . '/app/Http/Requests');

        foreach ($entities as $entity) {
            $name = $entity['name'] ?? null;
            $table = $entity['table'] ?? null;
            $fields = $entity['fields'] ?? [];

            if (!$name || !$table || $name === 'User') {
                continue;
            }

            $storeRules = $this->buildRules($fields, $table, false);
            $updateRules = $this->buildRules($fields, $table, true);

            $storeClass = "Store{$name}Request";
            $updateClass = "Update{$name}Request";

            $storeContent = <<<PHP
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class {$storeClass} extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
{$storeRules}
        ];
    }
}
PHP;

            $updateContent = <<<PHP
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class {$updateClass} extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        \$id = \$this->route('id');

        return [
{$updateRules}
        ];
    }
}
PHP;

            File::put($projectPath . "/app/Http/Requests/{$storeClass}.php", $storeContent);
            File::put($projectPath . "/app/Http/Requests/{$updateClass}.php", $updateContent);
        }
    }

    /**
     * Constrói regras Laravel validation a partir dos fields.
     *
     * @param array $fields
     * @param string $table
     * @param bool $isUpdate
     */
    protected function buildRules(array $fields, string $table, bool $isUpdate): string
    {
        $lines = [];

        foreach ($fields as $field) {
            $name = $field['name'] ?? null;
            $type = $field['type'] ?? 'string';
            $required = $field['required'] ?? false;
            $unique = $field['unique'] ?? false;

            if (!$name || in_array($name, ['id', 'created_at', 'updated_at'])) {
                continue;
            }

            $rules = [];

            if ($isUpdate) {
                $rules[] = "'sometimes'";
            } else {
                $rules[] = $required ? "'required'" : "'nullable'";
            }

            $rules[] = match ($type) {
                'string', 'text' => "'string'",
                'email' => "'email'",
                'integer' => "'integer'",
                'boolean' => "'boolean'",
                'date' => "'date'",
                'datetime' => "'date'",
                'decimal', 'float' => "'numeric'",
                default => "'string'",
            };

            if (in_array($type, ['string', 'email'])) {
                $rules[] = "'max:255'";
            }

            if ($unique) {
                if ($isUpdate) {
                    $rules[] = "Rule::unique('{$table}', '{$name}')->ignore(\$id)";
                } else {
                    $rules[] = "'unique:{$table},{$name}'";
                }
            }

            $ruleString = implode(', ', $rules);
            $lines[] = "            '{$name}' => [{$ruleString}],";
        }

        return implode("\n", $lines);
    }
}
