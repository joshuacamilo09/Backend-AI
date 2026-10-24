<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();

            // Geração a que este ficheiro pertence
            $table->foreignId('generation_id')->constrained()->cascadeOnDelete();

            // Caminho do ficheiro gerado
            $table->string('path');

            // Tipo do ficheiro: model, controller, migration, route, service...
            $table->string('type');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
