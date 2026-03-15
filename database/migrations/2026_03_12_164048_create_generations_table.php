<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('generations', function (Blueprint $table) {
            $table->id();

            // Projeto associado a esta geração
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();

            // Estado da geração
            // pending | processing | completed | failed
            $table->string('status');

            // Caminho do zip final, quando existir
            $table->string('output_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('generations');
    }
};
