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
        'imagen',
    ];

    protected function casts(): array
    {
        return [
            'precio' => 'float',
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
}