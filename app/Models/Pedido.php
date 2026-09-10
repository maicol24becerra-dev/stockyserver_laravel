<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    protected $table = 'pedido';

    protected $primaryKey = 'id_pedido';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'fecha',
        'estado',
        'prioridad',
        'id_usuario',
        'id_cliente',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'datetime',
        ];
    }

    /**
     * Usuario que registra el pedido.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'id_usuario',
            'id_usuario'
        );
    }

    /**
     * Cliente asociado al pedido.
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(
            Cliente::class,
            'id_cliente',
            'id_cliente'
        );
    }

    /**
     * Items que pertenecen al pedido.
     */
    public function items(): HasMany
    {
        return $this->hasMany(
            ItemPedido::class,
            'id_pedido',
            'id_pedido'
        );
    }

    /**
     * Pago asociado al pedido.
     */
    public function pago()
    {
        return $this->hasOne(
            Pago::class,
            'id_pedido',
            'id_pedido'
        );
    }
}