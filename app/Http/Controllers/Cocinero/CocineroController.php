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
        ->orderByDesc('fecha');


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
        | Por ahora no tenemos una columna de prioridad/urgencia
        | en la tabla pedido.
        |
        | Por eso se mantiene en 0 hasta implementar esa función.
        |
        */

        $urgentes = 0;


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
}