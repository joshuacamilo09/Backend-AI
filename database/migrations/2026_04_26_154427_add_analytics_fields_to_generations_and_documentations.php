<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adiciona campos usados no dashboard avançado de Analytics.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('generations', function (Blueprint $table) {
            // Tempo total da geração em milissegundos.
            $table->unsignedInteger('duration_ms')->nullable()->after('output_path');

            // Tamanho do ZIP gerado em bytes.
            $table->unsignedBigInteger('zip_size_bytes')->nullable()->after('duration_ms');

            // Número de vezes que o ZIP foi descarregado.
            $table->unsignedInteger('download_count')->default(0)->after('zip_size_bytes');

            // Tempo médio aproximado do download em ms.
            $table->unsignedInteger('avg_download_ms')->nullable()->after('download_count');
        });

        Schema::table('project_documentations', function (Blueprint $table) {
            // Número de vezes que a documentação foi descarregada em PDF/MD.
            $table->unsignedInteger('download_count')->default(0)->after('format');
        });

        Schema::table('project_documentations', function (Blueprint $table) {
            $table->unsignedInteger('duration_ms')->nullable()->after('download_count');
        });
    }

    public function down(): void
    {
        Schema::table('generations', function (Blueprint $table) {
            $table->dropColumn([
                'duration_ms',
                'zip_size_bytes',
                'download_count',
                'avg_download_ms',
            ]);
        });

        Schema::table('project_documentations', function (Blueprint $table) {
            $table->dropColumn('download_count');
        });
    }
};