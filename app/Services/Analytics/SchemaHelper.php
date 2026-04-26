<?php

namespace App\Services\Analytics;

use Illuminate\Support\Facades\Schema;

class SchemaHelper
{
    /**
     * Verifica se uma coluna existe numa tabela.
     *
     * Isto evita erros quando uma métrica ainda depende
     * de uma coluna futura, como template_key.
     */
    public static function hasColumn(string $table, string $column): bool
    {
        return Schema::hasColumn($table, $column);
    }
}
