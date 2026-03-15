<?php

namespace App\Services\Generation\Laravel;

use Illuminate\Support\Facades\File;

class ServiceGenerator
{
    /**
     * Gera services CRUD básicos por entidade.
     */
    public function generate(string $projectPath, array $spec): void
    {
        $entities = $spec['entities'] ?? [];

        File::ensureDirectoryExists($projectPath . '/app/Services');

        foreach ($entities as $entity) {
            $name = $entity['name'] ?? null;

            if (!$name) {
                continue;
            }

            $className = "{$name}Service";
            $variableName = lcfirst($name);

            $content = <<<PHP
<?php

namespace App\Services;

use App\Models\\{$name};

class {$className}
{
    /**
     * Listar todos os registos.
     */
    public function getAll()
    {
        return {$name}::all();
    }

    /**
     * Criar um novo registo.
     */
    public function create(array \$data)
    {
        return {$name}::create(\$data);
    }

    /**
     * Obter um registo por ID.
     */
    public function findById(int \$id)
    {
        return {$name}::findOrFail(\$id);
    }

    /**
     * Atualizar um registo existente.
     */
    public function update(int \$id, array \$data)
    {
        \${$variableName} = {$name}::findOrFail(\$id);
        \${$variableName}->update(\$data);

        return \${$variableName};
    }

    /**
     * Eliminar um registo.
     */
    public function delete(int \$id): void
    {
        \${$variableName} = {$name}::findOrFail(\$id);
        \${$variableName}->delete();
    }
}
PHP;

            File::put($projectPath . "/app/Services/{$className}.php", $content);
        }
    }
}
