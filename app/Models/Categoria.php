<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categoria';
    protected $primaryKey = 'id_categoria';

    protected $fillable = [
        'nombre',
        'descripcion',
        'icono',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Relación: Una categoría tiene muchos platos
     */
    public function platos()
    {
        return $this->hasMany(Plato::class, 'id_categoria', 'id_categoria');
    }
}
