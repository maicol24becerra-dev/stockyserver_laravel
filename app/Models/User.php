<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'nombre',
    'correo',
    'contrasena',
    'telefono',
    'id_rol',
    'estado',
    'reset_code',
    'reset_expiry',
])]

#[Hidden([

    'contrasena',
    'reset_code',
    'reset_expiry',
])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Nombre de la tabla.
     */
    protected $table = 'usuario';

    /**
     * Clave primaria.
     */
    protected $primaryKey = 'id_usuario';

    /**
     * La clave primaria es autoincremental.
     */
    public $incrementing = true;

    /**
     * Tipo de la clave primaria.
     */
    protected $keyType = 'int';

    /**
 * Nombre de la columna de contraseña.
 */
protected $authPasswordName = 'contrasena';

    /**
     * Casts de los atributos.
     */
    protected function casts(): array
    {
        return [
            'reset_expiry' => 'datetime',
            'contrasena' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    /**
 * Rol del usuario.
 */
public function role(): BelongsTo
{
    return $this->belongsTo(Role::class, 'id_rol', 'id_rol');
}

/**
 * Perfil de cliente asociado al usuario.
 */
public function cliente(): HasOne
{
    return $this->hasOne(Cliente::class, 'id_usuario', 'id_usuario');
}
/**
 * Obtener la contraseña utilizada por el sistema de autenticación.
 */
public function getAuthPassword(): string
{
    return $this->contrasena;
}
}
