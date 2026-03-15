<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'generation_id',
        'path',
        'type',
    ];

    /**
     * O ficheiro pertence a uma geração.
     */
    public function generation()
    {
        return $this->belongsTo(Generation::class);
    }
}
