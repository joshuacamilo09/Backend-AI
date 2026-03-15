<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /**
     * Campos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'user_id',
        'name',
        'framework',
        'description',
    ];

    /**
     * Um projecto pertence a um utilizador.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Um projecto tem uma specification.
     */
    public function specification()
    {
        return $this->hasOne(Specification::class);
    }

    /**
     * Um projecto pode ter várias gerações.
     */
    public function generations()
    {
        return $this->hasMany(Generation::class);
    }
}
