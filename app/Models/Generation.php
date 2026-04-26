<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Generation extends Model
{
    protected $fillable = [
        'duration_ms',
        'zip_size_bytes',
        'download_count',
        'avg_download_ms',
        'project_id',
        'status',
        'output_path',
    ];

    protected function casts(): array
    {
        return [
            'duration_ms' => 'integer',
            'zip_size_bytes' => 'integer',
            'download_count' => 'integer',
            'avg_download_ms' => 'integer',
        ];
    }

    /*
      A geração pertence a um projeto.
    */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Uma geração pode ter vários ficheiros.
     */
    public function files()
    {
        return $this->hasMany(File::class);
    }
}