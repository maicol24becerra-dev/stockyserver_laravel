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
            abort(403, 'El usuario no tiene un rol asignado.');
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
            abort(
                403,
                "No tienes permisos para acceder a esta sección. Rol actual: {$nombreRol}"
            );
        }

        return $next($request);
    }
}