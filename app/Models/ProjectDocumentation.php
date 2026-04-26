<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectDocumentation extends Model
{
    /**
     * Campos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'project_id',
        'content',
        'format',
        'download_count',
    ];

    protected function casts(): array
    {
        return [
            'download_count' => 'integer',
        ];
    }

    /**
     * Uma documentação pertence a um projeto.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}