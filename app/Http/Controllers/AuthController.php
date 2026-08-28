<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Mostrar formulario de inicio de sesión.
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Procesar inicio de sesión.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $usuario = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Verificar estado del usuario
        |--------------------------------------------------------------------------
        */

        if (strtolower($usuario->estado) !== 'activo') {

            Auth::logout();

            return back()->withErrors([
                'correo' => 'Contacte al administrador. La cuenta se encuentra inactiva.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Obtener rol
        |--------------------------------------------------------------------------
        */

        $rol = trim(
            $usuario->role?->nombre ?? ''
        );

        /*
        |--------------------------------------------------------------------------
        | Redirección según rol
        |--------------------------------------------------------------------------
        */

        return $this->redirectByRole($rol);
    }

    /**
     * Cerrar sesión.
     */
    public function logout(): RedirectResponse
    {
        Auth::logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Redirigir al usuario según su rol.
     */
    private function redirectByRole(?string $role): RedirectResponse
    {
        return match ($role) {

            'Administrador' =>
                redirect()->route('admin.dashboard'),

            'Mesero' =>
                redirect()->route('mesero.dashboard'),

            'Cocinero' =>
                redirect()->route('cocinero.dashboard'),

            'Cliente' =>
                redirect()->route('cliente.dashboard'),

            default => tap(
                redirect()->route('login'),
                function () {
                    Auth::logout();
                }
            ),
        };
    }
}
