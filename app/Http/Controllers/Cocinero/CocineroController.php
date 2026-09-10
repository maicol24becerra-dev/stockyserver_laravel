<?php

namespace App\Http\Controllers\Cocinero;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CocineroController extends Controller
{
    /**
     * Dashboard principal de cocina.
     */
    public function dashboard(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | Pedidos activos
        |--------------------------------------------------------------------------
        |
        | El cocinero trabaja únicamente con pedidos que todavía
        | están dentro del proceso de cocina.
        |
        */

        $query = Pedido::with([
            'cliente.usuario',
            'usuario',
            'items.plato',
        ])
        ->whereIn('estado', [
            'pendiente',
            'en preparación',
        ])
        ->orderByRaw("
            CASE prioridad 
                WHEN 'urgente' THEN 1 
                WHEN 'alta' THEN 2 
                WHEN 'normal' THEN 3 
                WHEN 'baja' THEN 4 
                ELSE 5 
            END
        ")
        ->orderBy('fecha', 'asc');


        /*
        |--------------------------------------------------------------------------
        | Filtro por estado
        |--------------------------------------------------------------------------
        */

        if ($request->filled('estado')) {

            $estado = $request->input('estado');

            if (in_array($estado, [
                'pendiente',
                'en preparación',
            ], true)) {

                $query->where('estado', $estado);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Pedidos para mostrar
        |--------------------------------------------------------------------------
        */

        $pedidos = $query->get();


        /*
        |--------------------------------------------------------------------------
        | Estadísticas
        |--------------------------------------------------------------------------
        */

        $pendientes = Pedido::where(
            'estado',
            'pendiente'
        )->count();


        $enPreparacion = Pedido::where(
            'estado',
            'en preparación'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Urgentes
        |--------------------------------------------------------------------------
        |
        | Pedidos con prioridad alta o urgente.
        |
        */

        $urgentes = Pedido::whereIn('estado', [
            'pendiente', 
            'en preparación'
        ])->whereIn('prioridad', ['alta', 'urgente'])->count();


        /*
        |--------------------------------------------------------------------------
        | Pedidos con hora de entrega
        |--------------------------------------------------------------------------
        |
        | Actualmente la tabla pedido no tiene una columna
        | hora_entrega.
        |
        | Por eso se mantiene en 0.
        |
        */

        $conHoraEntrega = 0;


        return view(
            'cocinero.dashboard',
            compact(
                'pedidos',
                'pendientes',
                'enPreparacion',
                'urgentes',
                'conHoraEntrega'
            )
        );
    }

    /**
     * Historial de pedidos completados/finalizados de cocina.
     */
    public function historial(Request $request): View
    {
        $query = Pedido::with([
            'cliente.usuario',
            'usuario',
            'items.plato',
        ])
        ->whereNotIn('estado', [
            'pendiente',
            'en preparación',
        ])
        ->orderBy('fecha', 'desc');

        if ($request->filled('estado')) {
            $estado = $request->input('estado');
            $query->where('estado', $estado);
        }

        $pedidos = $query->get();

        $pendientes = Pedido::where('estado', 'pendiente')->count();
        $enPreparacion = Pedido::where('estado', 'en preparación')->count();
        $urgentes = Pedido::whereIn('estado', ['pendiente', 'en preparación'])
            ->whereIn('prioridad', ['alta', 'urgente'])->count();
        $conHoraEntrega = 0;

        return view(
            'cocinero.historial',
            compact(
                'pedidos',
                'pendientes',
                'enPreparacion',
                'urgentes',
                'conHoraEntrega'
            )
        );
    }
}