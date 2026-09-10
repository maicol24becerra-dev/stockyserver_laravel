<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DestroySessionOnUnauthorized
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $response = $next($request);
            
            // Si la respuesta es un error 403 (Unauthorized/Forbidden)
            if ($response->status() === 403) {
                return $this->destroySessionAndRedirect($request);
            }
            
            return $response;
        } catch (HttpException $e) {
            // Capturar excepciones HTTP con código 403
            if ($e->getStatusCode() === 403) {
                return $this->destroySessionAndRedirect($request);
            }
            throw $e;
        }
    }

    /**
     * Destruir sesión y redirigir al login
     */
    private function destroySessionAndRedirect(Request $request)
    {
        // Cerrar sesión
        auth()->logout();
        
        // Invalidar sesión
        $request->session()->invalidate();
        
        // Regenerar token CSRF
        $request->session()->regenerateToken();
        
        // Redirigir al login con mensaje
        return redirect()->route('login')
            ->with('error', 'Tu sesión ha sido cerrada por intento de acceso no autorizado.');
    }
}
