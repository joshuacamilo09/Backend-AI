<?php

namespace App\Services\Generation\Laravel;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class RouteGenerator
{
    /**
     * Gera routes/api.php com rotas CRUD por entidade + auth.
     */
    public function generate(string $projectPath, array $spec): void
    {
        $entities = $spec['entities'] ?? [];
        $authEnabled = $spec['auth']['enabled'] ?? false;

        $imports = [];
        $routes = [];

        if ($authEnabled) {
            $imports[] = "use App\Http\Controllers\AuthController;";
        }

        foreach ($entities as $entity) {
            $name = $entity['name'] ?? null;
            $table = $entity['table'] ?? null;

            if (!$name || !$table || $name === 'User') {
                continue;
            }

            $controller = "{$name}Controller";
            $imports[] = "use App\Http\Controllers\\{$controller};";

            $resource = Str::kebab(Str::pluralStudly($name));

            $routes[] = "    Route::apiResource('{$resource}', {$controller}::class);";
        }

        $importsText = implode("\n", array_unique($imports));
        $crudRoutesText = implode("\n", $routes);

        $authRoutesText = '';

        if ($authEnabled) {
            $authRoutesText = <<<'PHP'
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
PHP;

            if (!empty($crudRoutesText)) {
                $authRoutesText .= "\n" . $crudRoutesText;
            }

            $authRoutesText .= "\n});";
        } else {
            $authRoutesText = $crudRoutesText;
        }

        $content = <<<PHP
<?php

use Illuminate\Support\Facades\Route;
{$importsText}

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Rotas API geradas automaticamente pela plataforma BackendAI.
|
*/

Route::get('/health', function () {
    return response()->json([
        'ok' => true,
        'message' => 'API is running',
    ]);
});

{$authRoutesText}
PHP;

        File::put($projectPath . '/routes/api.php', $content);
    }
}
