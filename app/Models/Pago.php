<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    protected $table = 'pago';

    protected $primaryKey = 'id_pago';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'monto_pagado',
        'metodo_pago',
        'fecha_pago',
        'id_pedido',
    ];

    protected function casts(): array
    {
        return [
            'monto_pagado' => 'float',
            'fecha_pago' => 'datetime',
        ];
    }

    /**
     * Pedido asociado al pago.
     */
    public function pedido(): BelongsTo
    {
        return $this->belongsTo(
            Pedido::class,
            'id_pedido',
            'id_pedido'
        );
    }
}