<?php

namespace App\Services\Generation;

use App\Models\Generation;
use App\Services\Generation\Laravel\AuthGenerator;
use App\Services\Generation\Laravel\ControllerGenerator;
use App\Services\Generation\Laravel\MigrationGenerator;
use App\Services\Generation\Laravel\RequestGenerator;
use App\Services\Generation\Laravel\RouteGenerator;
use App\Services\Generation\Laravel\ServiceGenerator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class LaravelProjectGenerator
{
    public function __construct(
        protected MigrationGenerator $migrationGenerator,
        protected ServiceGenerator $serviceGenerator,
        protected ControllerGenerator $controllerGenerator,
        protected RouteGenerator $routeGenerator,
        protected AuthGenerator $authGenerator,
        protected RequestGenerator $requestGenerator,
    ) {}

    /**
     * Gera um projeto Laravel real a partir da specification.
     */
    public function generate(array $spec, Generation $generation): string
    {
        $projectName = $spec['project_name'] ?? 'generated-project';
        $folderName = Str::kebab($projectName) . '-backend';

        $baseOutputPath = storage_path('app/generated-projects');

        if (!File::exists($baseOutputPath)) {
            File::makeDirectory($baseOutputPath, 0755, true);
        }

        $projectPath = $baseOutputPath . DIRECTORY_SEPARATOR . $folderName;

        if (File::exists($projectPath)) {
            File::deleteDirectory($projectPath);
        }

        $generation->update([
            'status' => 'processing',
        ]);

        // 1) Criar projeto Laravel real
        $this->createFreshLaravelProject($projectPath);

        // 2) Garantir docs
        File::ensureDirectoryExists($projectPath . '/docs');

        // 3) Guardar specification original em JSON
        File::put(
            $projectPath . '/docs/specification.json',
            json_encode($spec, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        // 4) Gerar README
        $this->generateReadme($projectPath, $spec);

        // 5) Gerar models com relações
        $this->generateModels($projectPath, $spec);

        // 6) Gerar migrations
        $this->migrationGenerator->generate($projectPath, $spec);

        // 7) Gerar services
        $this->serviceGenerator->generate($projectPath, $spec);

        // 8) Gerar Form Requests
        $this->requestGenerator->generate($projectPath, $spec);

        // 9) Gerar controllers CRUD
        $this->controllerGenerator->generate($projectPath, $spec);

        // 10) Gerar auth, se necessário
        if (($spec['auth']['enabled'] ?? false) === true) {
            $this->authGenerator->generate($projectPath, $spec);
        }

        // 11) Gerar rotas API
        $this->routeGenerator->generate($projectPath, $spec);

        return $projectPath;
    }

    /**
     * Usa o Composer para criar um projeto Laravel real.
     */
    protected function createFreshLaravelProject(string $projectPath): void
    {
        $phpBinary = trim(shell_exec('which php') ?? '');
        $composerBinary = trim(shell_exec('which composer') ?? '');

        if (empty($phpBinary)) {
            throw new \RuntimeException('PHP não foi encontrado no sistema.');
        }

        if (empty($composerBinary)) {
            throw new \RuntimeException('Composer não foi encontrado no sistema.');
        }

        $home = getenv('HOME') ?: ($_SERVER['HOME'] ?? null);

        if (empty($home)) {
            throw new \RuntimeException('A variável HOME não está definida no ambiente.');
        }

        $composerHome = $home . '/.composer';

        if (!File::exists($composerHome)) {
            File::makeDirectory($composerHome, 0755, true);
        }

        $env = array_merge($_ENV, $_SERVER, [
            'PATH' => dirname($phpBinary) . ':' . getenv('PATH'),
            'HOME' => $home,
            'COMPOSER_HOME' => $composerHome,
        ]);

        $process = new Process([
            $composerBinary,
            'create-project',
            'laravel/laravel',
            $projectPath,
        ]);

        $process->setEnv($env);
        $process->setTimeout(600);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException(
                "Erro ao criar projeto Laravel: " .
                $process->getErrorOutput() .
                $process->getOutput()
            );
        }
    }

    /**
     * Gera README do projeto criado.
     */
    protected function generateReadme(string $projectPath, array $spec): void
    {
        $projectName = $spec['project_name'] ?? 'Generated Project';
        $entities = $spec['entities'] ?? [];
        $authEnabled = ($spec['auth']['enabled'] ?? false) === true;

        $entityNames = collect($entities)
            ->pluck('name')
            ->implode(', ');

        $authSection = $authEnabled
            ? "- Sanctum authentication\n- /api/register\n- /api/login\n- /api/me\n- /api/logout"
            : "- No authentication generated";

        $readme = <<<MD
# {$projectName}

Este projeto foi gerado automaticamente pela plataforma BackendAI.

## Framework
Laravel

## Entidades detetadas
{$entityNames}

## Setup
1. composer install
2. cp .env.example .env
3. php artisan key:generate
4. configurar base de dados no ficheiro .env
5. php artisan migrate
6. php artisan serve

## Autenticação
{$authSection}

## Documentação
- docs/specification.json

## Estrutura gerada
- Models
- Migrations
- Services
- Form Requests
- Controllers
- Routes
- Auth (quando aplicável)

## Nota
Este backend foi gerado automaticamente e pode ser personalizado livremente.
MD;

        File::put($projectPath . '/README.md', $readme);
    }

    /**
     * Gera models Laravel com fillable + relações Eloquent básicas.
     */
    protected function generateModels(string $projectPath, array $spec): void
    {
        $entities = $spec['entities'] ?? [];
        $relations = $spec['relations'] ?? [];

        foreach ($entities as $entity) {
            $name = $entity['name'] ?? null;
            $fields = $entity['fields'] ?? [];

            if (!$name) {
                continue;
            }

            if ($name === 'User') {
                continue;
            }

            $fillable = collect($fields)
                ->pluck('name')
                ->filter(fn ($field) => !in_array($field, ['id', 'created_at', 'updated_at']))
                ->map(fn ($field) => "        '{$field}'")
                ->implode(",\n");

            $relationMethods = $this->buildModelRelations($name, $relations);

            $modelContent = <<<PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class {$name} extends Model
{
    /**
     * Campos que podem ser preenchidos em massa.
     */
    protected \$fillable = [
{$fillable}
    ];
{$relationMethods}
}
PHP;

            File::put(
                $projectPath . "/app/Models/{$name}.php",
                $modelContent
            );
        }
    }

    /**
     * Gera métodos de relações Eloquent para cada model.
     */
    protected function buildModelRelations(string $entityName, array $relations): string
    {
        $methods = [];

        foreach ($relations as $relation) {
            $type = $relation['type'] ?? null;
            $from = $relation['from'] ?? null;
            $to = $relation['to'] ?? null;
            $foreignKey = $relation['foreign_key'] ?? null;

            if ($type === 'one-to-many') {
                if ($from === $entityName) {
                    $methodName = str($to)->camel()->plural()->toString();
                    $methods[] = <<<PHP

    public function {$methodName}(): HasMany
    {
        return \$this->hasMany({$to}::class);
    }
PHP;
                }

                if ($to === $entityName) {
                    $methodName = str($from)->camel()->toString();
                    $fk = $foreignKey ?: str($from)->snake()->append('_id')->toString();

                    $methods[] = <<<PHP

    public function {$methodName}(): BelongsTo
    {
        return \$this->belongsTo({$from}::class, '{$fk}');
    }
PHP;
                }
            }

            if ($type === 'belongs-to' && $from === $entityName) {
                $methodName = str($to)->camel()->toString();
                $fk = $foreignKey ?: str($to)->snake()->append('_id')->toString();

                $methods[] = <<<PHP

    public function {$methodName}(): BelongsTo
    {
        return \$this->belongsTo({$to}::class, '{$fk}');
    }
PHP;
            }
        }

        return implode("\n", $methods);
    }
}
