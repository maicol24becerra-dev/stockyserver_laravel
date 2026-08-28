<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'nombre',
])]
class Role extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla.
     */
    protected $table = 'rol';

    /**
     * Clave primaria.
     */
    protected $primaryKey = 'id_rol';

    /**
     * Relación con los usuarios.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id_rol', 'id_rol');
    }
}

