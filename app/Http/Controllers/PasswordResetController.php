<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Mostrar formulario de recuperación
    |--------------------------------------------------------------------------
    */

    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }


    /*
    |--------------------------------------------------------------------------
    | Generar código de recuperación
    |--------------------------------------------------------------------------
    |
    | IMPORTANTE:
    | Solamente los usuarios con rol "Cliente" pueden
    | utilizar la recuperación de contraseña.
    |
    */

    public function sendCode(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validar correo
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'correo' => [
                'required',
                'email',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Buscar usuario junto con su rol
        |--------------------------------------------------------------------------
        */

        $usuario = User::with('role')
            ->where('correo', $request->correo)
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Verificar que exista
        |--------------------------------------------------------------------------
        */

        if (!$usuario) {

            return back()
                ->withErrors([
                    'correo' => 'No existe una cuenta registrada con ese correo.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | SOLO CLIENTES
        |--------------------------------------------------------------------------
        */

        if ($usuario->role?->nombre !== 'Cliente') {

            return back()
                ->withErrors([
                    'correo' => 'La recuperación de contraseña está disponible únicamente para cuentas de clientes.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Verificar estado de la cuenta
        |--------------------------------------------------------------------------
        */

        if (strtolower(trim($usuario->estado)) !== 'activo') {

            return back()
                ->withErrors([
                    'correo' => 'Esta cuenta se encuentra inactiva. Contacte al administrador.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Generar código de 6 dígitos
        |--------------------------------------------------------------------------
        */

        $codigo = random_int(100000, 999999);


        /*
        |--------------------------------------------------------------------------
        | Guardar código y fecha de expiración
        |--------------------------------------------------------------------------
        */

        $usuario->reset_code = $codigo;

        $usuario->reset_expiry = now()->addMinutes(15);

        $usuario->save();


        /*
        |--------------------------------------------------------------------------
        | Mostrar formulario para cambiar contraseña
        |--------------------------------------------------------------------------
        |
        | Por ahora mostramos el código en pantalla.
        | Posteriormente podemos conectarlo con correo electrónico.
        |
        */

        return redirect()
            ->route('password.reset')
            ->with('success', 'Código de recuperación generado correctamente.')
            ->with('correo', $usuario->correo)
            ->with('codigo', $codigo);
    }


    /*
    |--------------------------------------------------------------------------
    | Mostrar formulario para nueva contraseña
    |--------------------------------------------------------------------------
    */

    public function showResetForm()
    {
        return view('auth.reset-password', [
            'correo' => session('correo'),
            'codigo' => session('codigo'),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Cambiar contraseña
    |--------------------------------------------------------------------------
    */

    public function resetPassword(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validar datos
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'correo' => [
                'required',
                'email',
            ],

            'codigo' => [
                'required',
                'digits:6',
            ],

            'password' => [
                'required',
                'min:8',
                'confirmed',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Buscar usuario
        |--------------------------------------------------------------------------
        */

        $usuario = User::with('role')
            ->where('correo', $request->correo)
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Verificar que exista
        |--------------------------------------------------------------------------
        */

        if (!$usuario) {

            return back()
                ->withErrors([
                    'correo' => 'No existe una cuenta con ese correo.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | SOLO CLIENTES
        |--------------------------------------------------------------------------
        |
        | Esta segunda comprobación es importante.
        | Impide que un usuario de trabajo intente
        | saltarse el formulario de recuperación.
        |
        */

        if ($usuario->role?->nombre !== 'Cliente') {

            return back()
                ->withErrors([
                    'correo' => 'La recuperación de contraseña está disponible únicamente para cuentas de clientes.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Verificar estado de la cuenta
        |--------------------------------------------------------------------------
        */

        if (strtolower(trim($usuario->estado)) !== 'activo') {

            return back()
                ->withErrors([
                    'correo' => 'Esta cuenta se encuentra inactiva. Contacte al administrador.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Verificar código
        |--------------------------------------------------------------------------
        */

        if (
            $usuario->reset_code === null ||
            (string) $usuario->reset_code !== (string) $request->codigo
        ) {

            return back()
                ->withErrors([
                    'codigo' => 'El código de recuperación es incorrecto.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Verificar expiración
        |--------------------------------------------------------------------------
        */

        if (
            !$usuario->reset_expiry ||
            now()->greaterThan($usuario->reset_expiry)
        ) {

            /*
            | Limpiar código expirado
            */

            $usuario->reset_code = null;

            $usuario->reset_expiry = null;

            $usuario->save();


            return back()
                ->withErrors([
                    'codigo' => 'El código de recuperación ha expirado. Solicite uno nuevo.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Actualizar contraseña
        |--------------------------------------------------------------------------
        */

        $usuario->contrasena = Hash::make($request->password);


        /*
        |--------------------------------------------------------------------------
        | Eliminar código utilizado
        |--------------------------------------------------------------------------
        */

        $usuario->reset_code = null;

        $usuario->reset_expiry = null;

        $usuario->save();


        /*
        |--------------------------------------------------------------------------
        | Iniciar sesión automáticamente
        |--------------------------------------------------------------------------
        */

        Auth::login($usuario);

        $request->session()->regenerate();


        /*
        |--------------------------------------------------------------------------
        | Redirigir al Dashboard Cliente
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('cliente.dashboard')
            ->with(
                'success',
                'Contraseña actualizada correctamente.'
            );
    }
}