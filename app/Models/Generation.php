<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Generation extends Model
{
    protected $fillable = [
        'project_id',
        'status',
        'output_path',
    ];

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
