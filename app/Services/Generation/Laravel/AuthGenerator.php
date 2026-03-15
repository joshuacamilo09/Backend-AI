<?php

namespace App\Services\Generation\Laravel;

use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class AuthGenerator
{
    /**
     * Gera autenticação base com Laravel Sanctum.
     *
     * Fluxo:
     * 1. instalar Sanctum
     * 2. correr install:api sem migrar já
     * 3. criar AuthService
     * 4. criar AuthController
     * 5. atualizar User model com HasApiTokens
     */
    public function generate(string $projectPath, array $spec): void
    {
        $this->installSanctum($projectPath);
        $this->generateAuthService($projectPath);
        $this->generateAuthController($projectPath);
        $this->updateUserModel($projectPath);
    }

    /**
     * Instala Sanctum no projeto gerado.
     *
     * Importante:
     * - usamos uma SQLite local temporária dentro do projeto gerado
     * - evitamos usar a base de dados da plataforma principal
     * - dizemos "no" ao install:api quando ele perguntar se deve migrar já
     */
    protected function installSanctum(string $projectPath): void
    {
        $composerBinary = trim(shell_exec('which composer') ?? '');
        $phpBinary = trim(shell_exec('which php') ?? '');

        if (!$composerBinary || !$phpBinary) {
            throw new \RuntimeException('PHP ou Composer não encontrados para instalar Sanctum.');
        }

        $home = getenv('HOME') ?: ($_SERVER['HOME'] ?? null);

        if (empty($home)) {
            throw new \RuntimeException('A variável HOME não está definida no ambiente.');
        }

        $composerHome = $home . '/.composer';

        if (!File::exists($composerHome)) {
            File::makeDirectory($composerHome, 0755, true);
        }

        // Criar uma SQLite local só para o processo do projeto gerado
        $sqlitePath = $projectPath . '/database/database.sqlite';

        if (!File::exists(dirname($sqlitePath))) {
            File::makeDirectory(dirname($sqlitePath), 0755, true);
        }

        if (!File::exists($sqlitePath)) {
            File::put($sqlitePath, '');
        }

        /**
         * MUITO IMPORTANTE:
         * sobrescrevemos DB_* para o processo filho,
         * senão ele herda a BD da plataforma principal.
         */
        $env = array_merge($_ENV, $_SERVER, [
            'PATH' => dirname($phpBinary) . ':' . getenv('PATH'),
            'HOME' => $home,
            'COMPOSER_HOME' => $composerHome,

            // Base de dados isolada para comandos do projeto gerado
            'DB_CONNECTION' => 'sqlite',
            'DB_DATABASE' => $sqlitePath,
            'DB_HOST' => null,
            'DB_PORT' => null,
            'DB_USERNAME' => null,
            'DB_PASSWORD' => null,
        ]);

        // 1) instalar package laravel/sanctum
        $requireProcess = new Process([
            $composerBinary,
            'require',
            'laravel/sanctum',
        ], $projectPath);

        $requireProcess->setEnv($env);
        $requireProcess->setTimeout(600);
        $requireProcess->run();

        if (!$requireProcess->isSuccessful()) {
            throw new \RuntimeException(
                'Erro ao instalar laravel/sanctum: ' .
                $requireProcess->getErrorOutput() .
                $requireProcess->getOutput()
            );
        }

        // 2) instalar API stack moderna
        $installApiProcess = new Process([
            $phpBinary,
            'artisan',
            'install:api',
        ], $projectPath);

        $installApiProcess->setEnv($env);
        $installApiProcess->setTimeout(600);

        /**
         * O comando pergunta:
         * "Would you like to run all pending database migrations?"
         *
         * Respondemos "no" para não migrar já.
         * O utilizador fará isso depois no projeto gerado.
         */
        $installApiProcess->setInput("no\n");
        $installApiProcess->run();

        if (!$installApiProcess->isSuccessful()) {
            throw new \RuntimeException(
                'Erro ao executar install:api: ' .
                $installApiProcess->getErrorOutput() .
                $installApiProcess->getOutput()
            );
        }
    }

    /**
     * Gera app/Services/AuthService.php
     */
    protected function generateAuthService(string $projectPath): void
    {
        File::ensureDirectoryExists($projectPath . '/app/Services');

        $content = <<<'PHP'
<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Regista um novo utilizador e devolve token.
     */
    public function register(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Faz login e devolve token.
     */
    public function login(array $data): array
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Logout do utilizador atual.
     */
    public function logout(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }
}
PHP;

        File::put($projectPath . '/app/Services/AuthService.php', $content);
    }

    /**
     * Gera app/Http/Controllers/AuthController.php
     */
    protected function generateAuthController(string $projectPath): void
    {
        File::ensureDirectoryExists($projectPath . '/app/Http/Controllers');

        $content = <<<'PHP'
<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * Registo de utilizador.
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $result = $this->authService->register($data);

        return response()->json($result, 201);
    }

    /**
     * Login.
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $result = $this->authService->login($data);

        return response()->json($result);
    }

    /**
     * Logout.
     */
    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    /**
     * Utilizador autenticado atual.
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
PHP;

        File::put($projectPath . '/app/Http/Controllers/AuthController.php', $content);
    }

    /**
     * Atualiza app/Models/User.php para incluir HasApiTokens.
     */
    protected function updateUserModel(string $projectPath): void
    {
        $userPath = $projectPath . '/app/Models/User.php';

        if (!File::exists($userPath)) {
            return;
        }

        $content = File::get($userPath);

        if (!str_contains($content, 'Laravel\Sanctum\HasApiTokens')) {
            $content = str_replace(
                "use Illuminate\Foundation\Auth\User as Authenticatable;",
                "use Illuminate\Foundation\Auth\User as Authenticatable;\nuse Laravel\Sanctum\HasApiTokens;",
                $content
            );
        }

        if (!str_contains($content, 'HasApiTokens,')) {
            $content = preg_replace(
                '/use\s+HasFactory,\s*Notifiable;/',
                'use HasApiTokens, HasFactory, Notifiable;',
                $content
            );
        }

        File::put($userPath, $content);
    }
}