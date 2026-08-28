<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MateriaPrima extends Model
{
    protected $table = 'materia_prima';

    protected $primaryKey = 'id_materia';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'nombre',
        'stock_actual',
        'unidad_medida',
        'stock_minimo',
    ];

    protected function casts(): array
    {
        return [
            'stock_actual' => 'float',
            'stock_minimo' => 'float',
            'ultima_actualizacion' => 'datetime',
        ];
    }

    public function recetas(): HasMany
    {
        return $this->hasMany(
            Receta::class,
            'id_materia',
            'id_materia'
        );
    }
}