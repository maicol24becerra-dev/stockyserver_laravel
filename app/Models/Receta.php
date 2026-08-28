<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receta extends Model
{
    protected $table = 'receta';

    protected $primaryKey = 'id_receta';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'id_plato',
        'id_materia',
        'cantidad_requerida',
    ];

    protected function casts(): array
    {
        return [
            'cantidad_requerida' => 'float',
        ];
    }

    public function materiaPrima(): BelongsTo
    {
        return $this->belongsTo(
            MateriaPrima::class,
            'id_materia',
            'id_materia'
        );
    }

    public function plato(): BelongsTo
    {
        return $this->belongsTo(
            Plato::class,
            'id_plato',
            'id_plato'
        );
    }
}