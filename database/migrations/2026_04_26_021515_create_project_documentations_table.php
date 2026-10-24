<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Guarda a documentação gerada para cada projeto.
 *
 * A documentação será gerada em Markdown e poderá ser visualizada
 * dentro da plataforma BackendAI.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_documentations', function (Blueprint $table) {
            $table->id();

            // Cada documentação pertence a um projeto.
            $table->foreignId('project_id')
                ->constrained()
                ->cascadeOnDelete();

            // Conteúdo completo da documentação em Markdown.
            $table->longText('content');

            // Formato da documentação. Para já será sempre markdown.
            $table->string('format')->default('markdown');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_documentations');
    }
};
