<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adiciona permissões e dados SIG/geográficos ao utilizador.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Define se o utilizador é user normal ou admin.
            $table->string('role')->default('user')->after('password');

            // Coordenadas captadas pelo browser no momento do registo.
            $table->decimal('latitude', 10, 7)->nullable()->after('role');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');

            // Campos opcionais para análises futuras.
            $table->string('country')->nullable()->after('longitude');
            $table->string('city')->nullable()->after('country');
            $table->string('continent')->nullable()->after('city');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'latitude',
                'longitude',
                'country',
                'city',
                'continent',
            ]);
        });
    }
};
