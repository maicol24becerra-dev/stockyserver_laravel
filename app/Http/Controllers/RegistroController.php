<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegistroController extends Controller
{
    /**
     * Mostrar formulario de registro.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Registrar nuevo cliente.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
            ],

            'correo' => [
                'required',
                'email',
                'max:255',
                'unique:usuario,correo',
            ],

            'telefono' => [
                'nullable',
                'string',
                'max:20',
            ],

            'contrasena' => [
                'required',
                'string',
                'min:6',
            ],
        ], [
            'nombre.required' => 'El nombre es obligatorio.',

            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'Ingresa un correo electrónico válido.',
            'correo.unique' => 'Este correo ya está registrado.',

            'telefono.max' => 'El teléfono no puede superar los 20 caracteres.',

            'contrasena.required' => 'La contraseña es obligatoria.',
            'contrasena.min' => 'La contraseña debe tener mínimo 6 caracteres.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Buscar rol Cliente
        |--------------------------------------------------------------------------
        */

        $rolCliente = Role::where('nombre', 'Cliente')->first();

        if (!$rolCliente) {
            return back()
                ->withInput()
                ->withErrors([
                    'correo' => 'No existe el rol Cliente en el sistema.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Crear usuario
        |--------------------------------------------------------------------------
        */

        User::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'telefono' => $request->telefono,

            'contrasena' => Hash::make(
                $request->contrasena
            ),

            'id_rol' => $rolCliente->id_rol,

            'estado' => 'activo',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Redirigir al login
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Cuenta creada correctamente. Ahora puedes iniciar sesión.'
            );
    }
}