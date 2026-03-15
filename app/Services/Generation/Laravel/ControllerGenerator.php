<?php

namespace App\Services\Generation\Laravel;

use Illuminate\Support\Facades\File;

class ControllerGenerator
{
    /**
     * Gera controllers CRUD básicos por entidade,
     * agora usando Form Requests de validação.
     */
    public function generate(string $projectPath, array $spec): void
    {
        $entities = $spec['entities'] ?? [];

        File::ensureDirectoryExists($projectPath . '/app/Http/Controllers');

        foreach ($entities as $entity) {
            $name = $entity['name'] ?? null;

            if (!$name || $name === 'User') {
                continue;
            }

            $className = "{$name}Controller";
            $serviceName = "{$name}Service";
            $variableName = lcfirst($name);
            $storeRequest = "Store{$name}Request";
            $updateRequest = "Update{$name}Request";

            $content = <<<PHP
<?php

namespace App\Http\Controllers;

use App\Http\Requests\\{$storeRequest};
use App\Http\Requests\\{$updateRequest};
use App\Services\\{$serviceName};

class {$className} extends Controller
{
    public function __construct(
        protected {$serviceName} \${$variableName}Service
    ) {}

    /**
     * Listar todos os registos.
     */
    public function index()
    {
        return response()->json(
            \$this->{$variableName}Service->getAll()
        );
    }

    /**
     * Criar um novo registo.
     */
    public function store({$storeRequest} \$request)
    {
        \$data = \$request->validated();

        \$created = \$this->{$variableName}Service->create(\$data);

        return response()->json(\$created, 201);
    }

    /**
     * Obter um registo por ID.
     */
    public function show(int \$id)
    {
        return response()->json(
            \$this->{$variableName}Service->findById(\$id)
        );
    }

    /**
     * Atualizar um registo.
     */
    public function update({$updateRequest} \$request, int \$id)
    {
        \$updated = \$this->{$variableName}Service->update(\$id, \$request->validated());

        return response()->json(\$updated);
    }

    /**
     * Eliminar um registo.
     */
    public function destroy(int \$id)
    {
        \$this->{$variableName}Service->delete(\$id);

        return response()->json([
            'message' => '{$name} deleted successfully.'
        ]);
    }
}
PHP;

            File::put($projectPath . "/app/Http/Controllers/{$className}.php", $content);
        }
    }
}