<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_documentations', function (Blueprint $table) {
            if (!Schema::hasColumn('project_documentations', 'duration_ms')) {
                $table->unsignedInteger('duration_ms')->nullable()->after('download_count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('project_documentations', function (Blueprint $table) {
            if (Schema::hasColumn('project_documentations', 'duration_ms')) {
                $table->dropColumn('duration_ms');
            }
        });
    }
};
