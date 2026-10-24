<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specification extends Model
{
    protected $fillable = [
        'project_id',
        'spec',
    ];

    /**
     * Converte automaticamente o JSON da BD para array PHP.
     */
    protected $casts = [
        'spec' => 'array',
    ];

    /**
     * Esta specification pertence a um projeto.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
