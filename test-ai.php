<?php

use App\Models\User;
use App\Services\AI\AIInterpreter;
use Illuminate\Support\Facades\Auth;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// 1. Encontrar ou criar um utilizador de teste
$user = User::first() ?: User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('password'),
]);

Auth::login($user);

// 2. Tentar usar o AIInterpreter
$interpreter = app(AIInterpreter::class);
$description = "I want a simple blog system with posts and comments. Each post has a title and content. Comments belong to a post.";

echo "Testing AIInterpreter with description: \"$description\"\n";

try {
    $spec = $interpreter->parse($description);
    echo "SUCCESS! Received specification:\n";
    echo json_encode($spec, JSON_PRETTY_PRINT) . "\n";
} catch (\Throwable $e) {
    echo "FAILURE!\n";
    echo "Error: " . $e->getMessage() . "\n";
    if (isset($e->getTrace()[0])) {
        echo "Location: " . $e->getFile() . ":" . $e->getLine() . "\n";
    }
}
