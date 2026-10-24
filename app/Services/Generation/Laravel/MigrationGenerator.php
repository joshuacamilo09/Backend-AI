<?php

namespace App\Services\Generation\Laravel;

use Illuminate\Support\Facades\File;

class MigrationGenerator
{
    /**
     * Gera migrations Laravel com base nas entidades da specification.
     */
    public function generate(string $projectPath, array $spec): void
    {
        $entities = $spec['entities'] ?? [];
        $relations = $spec['relations'] ?? [];

        foreach ($entities as $index => $entity) {
            $entityName = $entity['name'] ?? null;
            $tableName = $entity['table'] ?? null;
            $fields = $entity['fields'] ?? [];

            if (!$entityName || !$tableName) {
                continue;
            }

            // O Laravel já traz uma migration própria para a tabela users.
            // Se gerarmos outra migration para User, dá erro de tabela duplicada.
            if ($entityName === 'User' || $tableName === 'users') {
                continue;
            }

            $timestamp = now()->addSeconds($index)->format('Y_m_d_His');
            $fileName = "{$timestamp}_create_{$tableName}_table.php";
            $filePath = $projectPath . "/database/migrations/{$fileName}";

            $columns = $this->buildColumns($fields, $entityName, $relations);

            $content = <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('{$tableName}', function (Blueprint \$table) {
            \$table->id();
{$columns}
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('{$tableName}');
    }
};
PHP;

            File::put($filePath, $content);
        }
    }

    /**
     * Converte os fields da spec em colunas de migration Laravel,
     * incluindo foreign keys quando aplicável.
     */
    protected function buildColumns(array $fields, string $entityName, array $relations): string
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

            $relation = $this->findBelongsToRelationForField($entityName, $name, $relations);

            if ($relation) {
                $relatedTable = $this->entityToTableName($relation['to']);

                $column = "\$table->foreignId('{$name}')->constrained('{$relatedTable}')->cascadeOnDelete()";

                if (!$required) {
                    $column .= '->nullable()';
                }

                $column .= ';';

                $lines[] = "            {$column}";
                continue;
            }

            $column = $this->mapFieldToLaravelColumn($name, $type);

            if (!$required) {
                $column .= '->nullable()';
            }

            if ($unique) {
                $column .= '->unique()';
            }

            $column .= ';';

            $lines[] = "            {$column}";
        }

        return implode("\n", $lines);
    }

    /**
     * Descobre se um field corresponde a uma relação belongsTo.
     */
    protected function findBelongsToRelationForField(string $entityName, string $fieldName, array $relations): ?array
    {
        foreach ($relations as $relation) {
            $type = $relation['type'] ?? null;
            $from = $relation['from'] ?? null;
            $to = $relation['to'] ?? null;
            $foreignKey = $relation['foreign_key'] ?? null;

            if ($type === 'belongs-to' && $from === $entityName && $foreignKey === $fieldName) {
                return $relation;
            }

            if ($type === 'one-to-many' && $to === $entityName && $foreignKey === $fieldName) {
                return [
                    'type' => 'belongs-to',
                    'from' => $entityName,
                    'to' => $from,
                    'foreign_key' => $fieldName,
                ];
            }
        }

        return null;
    }

    /**
     * Faz o mapeamento dos tipos genéricos para métodos Laravel migration.
     */
    protected function mapFieldToLaravelColumn(string $name, string $type): string
    {
        return match ($type) {
            'string', 'email' => "\$table->string('{$name}')",
            'text' => "\$table->text('{$name}')",
            'integer' => "\$table->integer('{$name}')",
            'boolean' => "\$table->boolean('{$name}')",
            'date' => "\$table->date('{$name}')",
            'datetime' => "\$table->dateTime('{$name}')",
            'decimal', 'float' => "\$table->decimal('{$name}', 10, 2)",
            default => "\$table->string('{$name}')",
        };
    }

    /**
     * Converte nome de entidade em nome de tabela.
     */
    protected function entityToTableName(string $entityName): string
    {
        return str($entityName)->snake()->plural()->toString();
    }
}