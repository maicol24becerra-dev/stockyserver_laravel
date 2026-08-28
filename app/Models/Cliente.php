<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Pedido;

class Cliente extends Model
{
    protected $table = 'cliente';

    protected $primaryKey = 'id_cliente';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'id_usuario',
    ];

    /**
     * Usuario asociado al cliente.
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
     * Pedidos realizados por el cliente.
     */
    public function pedidos(): HasMany
    {
        return $this->hasMany(
            Pedido::class,
            'id_cliente',
            'id_cliente'
        );
    }
}