<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plato extends Model
{
    protected $table = 'plato';

    protected $primaryKey = 'id_plato';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'nombre',
        'precio',
        'descripcion',
        'disponibilidad',
        'categoria',
        'id_categoria',
        'imagen',
        'calorias',
        'proteinas',
        'carbohidratos',
        'grasas',
        'alergenos',
        'vegetariano',
        'vegano',
        'sin_gluten',
        'ingredientes_principales',
        'tiempo_preparacion',
        'nivel_picante',
    ];

    protected function casts(): array
    {
        return [
            'precio' => 'float',
            'vegetariano' => 'boolean',
            'vegano' => 'boolean',
            'sin_gluten' => 'boolean',
        ];
    }

    public function recetas(): HasMany
    {
        return $this->hasMany(
            Receta::class,
            'id_plato',
            'id_plato'
        );
    }

    /**
     * Relación: Un plato pertenece a una categoría
     */
    public function categoriaRelacion()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }
}