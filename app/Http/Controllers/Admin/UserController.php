<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Listar usuarios
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $usuarios = User::with('role')
            ->orderByDesc('id_usuario')
            ->paginate(10);

        return view('admin.usuarios.index', compact('usuarios'));
    }


    /*
    |--------------------------------------------------------------------------
    | Mostrar formulario para crear usuario
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        /*
        | Los clientes NO se crean desde el panel administrativo.
        | Se registran mediante /register.
        */

        $roles = Role::whereIn('nombre', [
            'Administrador',
            'Mesero',
            'Cocinero',
        ])->get();

        return view('admin.usuarios.create', compact('roles'));
    }


    /*
    |--------------------------------------------------------------------------
    | Guardar usuario
    |--------------------------------------------------------------------------
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

            'id_rol' => [
                'required',
                'exists:rol,id_rol',
            ],

            'contrasena' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);


        $rol = Role::findOrFail($request->id_rol);


        /*
        |--------------------------------------------------------------------------
        | No permitir crear clientes desde el administrador
        |--------------------------------------------------------------------------
        */

        if ($rol->nombre === 'Cliente') {
            return back()
                ->withInput()
                ->withErrors([
                    'id_rol' => 'Los clientes deben registrarse mediante el formulario público.',
                ]);
        }


        User::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'id_rol' => $request->id_rol,
            'contrasena' => Hash::make($request->contrasena),
            'estado' => 'activo',
        ]);


        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }


    /*
    |--------------------------------------------------------------------------
    | Mostrar usuario
    |--------------------------------------------------------------------------
    */

    public function show(User $usuario)
    {
        $usuario->load('role');

        return view(
            'admin.usuarios.show',
            compact('usuario')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Mostrar formulario de edición
    |--------------------------------------------------------------------------
    */

    public function edit(User $usuario)
    {
        /*
        | Los clientes tampoco se pueden asignar desde aquí.
        */

        $roles = Role::whereIn('nombre', [
            'Administrador',
            'Mesero',
            'Cocinero',
        ])->get();

        return view(
            'admin.usuarios.edit',
            compact('usuario', 'roles')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Actualizar usuario
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, User $usuario)
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
                'unique:usuario,correo,' . $usuario->id_usuario . ',id_usuario',
            ],

            'telefono' => [
                'nullable',
                'string',
                'max:20',
            ],

            'id_rol' => [
                'required',
                'exists:rol,id_rol',
            ],
        ]);


        $rol = Role::findOrFail($request->id_rol);


        /*
        |--------------------------------------------------------------------------
        | No convertir empleados en clientes
        |--------------------------------------------------------------------------
        */

        if ($rol->nombre === 'Cliente') {
            return back()
                ->withInput()
                ->withErrors([
                    'id_rol' => 'Las cuentas de clientes se gestionan mediante el registro público.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Proteger al administrador actual
        |--------------------------------------------------------------------------
        */

        if (
            $usuario->id_usuario === auth()->id()
            && $rol->nombre !== 'Administrador'
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'id_rol' => 'No puedes quitarte el rol de Administrador.',
                ]);
        }


        $usuario->update([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'id_rol' => $request->id_rol,
        ]);


        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }


    /*
    |--------------------------------------------------------------------------
    | Eliminar usuario
    |--------------------------------------------------------------------------
    */

    public function destroy(User $usuario)
    {
        /*
        | No permitir que el administrador elimine su propia cuenta.
        */

        if ($usuario->id_usuario === auth()->id()) {
            return back()->withErrors([
                'usuario' => 'No puedes eliminar tu propia cuenta.',
            ]);
        }


        /*
        | No eliminar clientes desde este módulo.
        */

        if ($usuario->role?->nombre === 'Cliente') {
            return back()->withErrors([
                'usuario' => 'Las cuentas de clientes no se eliminan desde el panel administrativo.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | CRÍTICO: Validar pedidos activos antes de eliminar (HU-02C)
        |--------------------------------------------------------------------------
        | Un usuario NO puede ser eliminado si tiene pedidos en estados:
        | - pendiente
        | - en preparación
        | - listo
        */

        $pedidosActivos = \App\Models\Pedido::where('id_usuario', $usuario->id_usuario)
            ->whereNotIn('estado', ['entregado', 'cancelado'])
            ->count();

        if ($pedidosActivos > 0) {
            return back()->withErrors([
                'usuario' => sprintf(
                    'No se puede eliminar a %s porque tiene %d pedido(s) activo(s). Completa o cancela los pedidos primero.',
                    $usuario->nombre,
                    $pedidosActivos
                ),
            ]);
        }


        $usuario->delete();


        return redirect()
            ->route('admin.usuarios.index')
            ->with(
                'success',
                'Usuario eliminado correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Mostrar formulario para cambiar contraseña
    |--------------------------------------------------------------------------
    */

    public function editPassword(User $usuario)
    {
        return view(
            'admin.usuarios.password',
            compact('usuario')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Actualizar contraseña
    |--------------------------------------------------------------------------
    */

    public function updatePassword(Request $request, User $usuario)
    {
        $request->validate([
            'contrasena' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);


        $usuario->update([
            'contrasena' => Hash::make(
                $request->contrasena
            ),
        ]);


        return redirect()
            ->route('admin.usuarios.index')
            ->with(
                'success',
                'La contraseña de ' . $usuario->nombre . ' fue actualizada correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Activar cuenta
    |--------------------------------------------------------------------------
    */

    public function activar(User $usuario)
    {
        $usuario->update([
            'estado' => 'activo',
        ]);


        return redirect()
            ->route('admin.usuarios.index')
            ->with(
                'success',
                'La cuenta de ' . $usuario->nombre . ' fue activada correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Desactivar cuenta
    |--------------------------------------------------------------------------
    */

    public function desactivar(User $usuario)
    {
        /*
        | No permitir que el administrador se desactive a sí mismo.
        */

        if ($usuario->id_usuario === auth()->id()) {
            return back()->withErrors([
                'usuario' => 'No puedes desactivar tu propia cuenta.',
            ]);
        }


        $usuario->update([
            'estado' => 'inactivo',
        ]);


        return redirect()
            ->route('admin.usuarios.index')
            ->with(
                'success',
                'La cuenta de ' . $usuario->nombre . ' fue desactivada correctamente.'
            );
    }
}