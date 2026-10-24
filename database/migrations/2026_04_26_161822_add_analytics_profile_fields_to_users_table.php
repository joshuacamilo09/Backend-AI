<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adiciona campos de perfil e atividade para analytics avançado.
 *
 * Usamos Schema::hasColumn para evitar erro caso alguma coluna
 * já exista na base de dados.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'user_type')) {
                $table->string('user_type')->nullable();
            }

            if (!Schema::hasColumn('users', 'experience_level')) {
                $table->string('experience_level')->nullable();
            }

            if (!Schema::hasColumn('users', 'main_interest')) {
                $table->string('main_interest')->nullable();
            }

            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable();
            }

            if (!Schema::hasColumn('users', 'last_activity_at')) {
                $table->timestamp('last_activity_at')->nullable();
            }

            if (!Schema::hasColumn('users', 'login_count')) {
                $table->unsignedInteger('login_count')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'user_type')) {
                $table->dropColumn('user_type');
            }

            if (Schema::hasColumn('users', 'experience_level')) {
                $table->dropColumn('experience_level');
            }

            if (Schema::hasColumn('users', 'main_interest')) {
                $table->dropColumn('main_interest');
            }

            if (Schema::hasColumn('users', 'last_login_at')) {
                $table->dropColumn('last_login_at');
            }

            if (Schema::hasColumn('users', 'last_activity_at')) {
                $table->dropColumn('last_activity_at');
            }

            if (Schema::hasColumn('users', 'login_count')) {
                $table->dropColumn('login_count');
            }
        });
    }
};
