<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('specifications', function (Blueprint $table) {
            $table->id();

            // Projeto ao qual esta specification pertence
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();

            // JSON devolvido pela IA
            $table->json('spec');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('specifications');
    }
};
