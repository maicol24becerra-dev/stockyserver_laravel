<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ): Response {

        // Usuario autenticado
        $usuario = $request->user();

        if (!$usuario) {
            return redirect()->route('login');
        }

        // Obtener rol del usuario
        $rol = $usuario->role;

        if (!$rol) {
            return $this->destroySessionAndRedirect($request, 'El usuario no tiene un rol asignado.');
        }

        // Nombre del rol
        $nombreRol = trim($rol->nombre);

        // Roles permitidos
        $rolesPermitidos = array_map(
            fn ($role) => trim($role),
            $roles
        );

        // Comprobar permiso
        if (!in_array($nombreRol, $rolesPermitidos, true)) {
            return $this->destroySessionAndRedirect(
                $request, 
                "Acceso no autorizado. Rol actual: {$nombreRol}"
            );
        }

        return $next($request);
    }

    /**
     * Destruir sesión y redirigir al login
     */
    private function destroySessionAndRedirect(Request $request, string $message)
    {
        // Cerrar sesión
        auth()->logout();
        
        // Invalidar sesión
        $request->session()->invalidate();
        
        // Regenerar token CSRF
        $request->session()->regenerateToken();
        
        // Redirigir al login con mensaje de error
        return redirect()->route('login')
            ->with('error', 'Tu sesión ha sido cerrada. ' . $message);
    }
}