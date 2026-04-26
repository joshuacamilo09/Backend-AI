<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectEndpoint extends Model
{
    /**
     * Campos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'project_id',
        'method',
        'path',
        'name',
        'description',
        'requires_auth',
        'sample_body',
    ];

    /**
     * Casts convertem automaticamente certos campos.
     */
    protected function casts(): array
    {
        return [
            'requires_auth' => 'boolean',
            'sample_body' => 'array',
        ];
    }

    /**
     * Um endpoint pertence a um projeto.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
