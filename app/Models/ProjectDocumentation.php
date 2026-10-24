<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectDocumentation extends Model
{
    protected $fillable = [
        'project_id',
        'content',
        'format',
        'download_count',
        'duration_ms',
    ];

    protected function casts(): array
    {
        return [
            'download_count' => 'integer',
            'duration_ms' => 'integer',
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
