<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Esta tabela guarda os endpoints que o BackendAI identifica
 * para cada projeto gerado.
 *
 * Exemplo:
 * - GET /api/books
 * - POST /api/books
 * - GET /api/books/{id}
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_endpoints', function (Blueprint $table) {
            $table->id();

            // Cada endpoint pertence a um projeto gerado.
            $table->foreignId('project_id')
                ->constrained()
                ->cascadeOnDelete();

            // Método HTTP: GET, POST, PUT, PATCH, DELETE.
            $table->string('method');

            // Caminho do endpoint dentro do backend gerado.
            // Exemplo: /api/books ou /api/books/{id}
            $table->string('path');

            // Nome amigável para aparecer na interface.
            // Exemplo: List Books, Create Book, Delete Book
            $table->string('name');

            // Descrição curta do que o endpoint faz.
            $table->text('description')->nullable();

            // Indica se o endpoint precisa de autenticação.
            $table->boolean('requires_auth')->default(false);

            // Body de exemplo para POST/PUT/PATCH.
            $table->json('sample_body')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_endpoints');
    }
};
