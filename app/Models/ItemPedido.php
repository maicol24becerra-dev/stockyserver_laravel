<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemPedido extends Model
{
    protected $table = 'item_pedido';

    protected $primaryKey = 'id_item_pedido';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'cantidad',
        'precio_unitario',
        'id_pedido',
        'id_plato',
    ];

    protected function casts(): array
    {
        return [
            'cantidad' => 'integer',
            'precio_unitario' => 'float',
        ];
    }

    /**
     * Pedido al que pertenece el item.
     */
    public function pedido(): BelongsTo
    {
        return $this->belongsTo(
            Pedido::class,
            'id_pedido',
            'id_pedido'
        );
    }

    /**
     * Plato incluido en el pedido.
     */
    public function plato(): BelongsTo
    {
        return $this->belongsTo(
            Plato::class,
            'id_plato',
            'id_plato'
        );
    }
}