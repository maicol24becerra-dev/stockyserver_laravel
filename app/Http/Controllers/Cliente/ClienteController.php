<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ClienteController extends Controller
{
    /**
     * Dashboard del cliente.
     */
    public function dashboard(): View
    {
        $usuario = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Buscar el cliente relacionado con el usuario
        |--------------------------------------------------------------------------
        */

        $cliente = $usuario->cliente;

        /*
        |--------------------------------------------------------------------------
        | Pedidos del cliente
        |--------------------------------------------------------------------------
        */

        $pedidos = collect();

        if ($cliente) {
            $pedidos = Pedido::with([
                'items.plato',
                'pago',
            ])
                ->where('id_cliente', $cliente->id_cliente)
                ->orderByDesc('fecha')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Estadísticas
        |--------------------------------------------------------------------------
        */

        $totalPedidos = $pedidos->count();

        $pedidosPendientes = $pedidos->where(
            'estado',
            'pendiente'
        )->count();

        $pedidosEnPreparacion = $pedidos->where(
            'estado',
            'en preparación'
        )->count();

        $pedidosListos = $pedidos->where(
            'estado',
            'listo'
        )->count();

        $pedidosEntregados = $pedidos->where(
            'estado',
            'entregado'
        )->count();

        return view('cliente.dashboard', compact(
            'usuario',
            'cliente',
            'pedidos',
            'totalPedidos',
            'pedidosPendientes',
            'pedidosEnPreparacion',
            'pedidosListos',
            'pedidosEntregados'
        ));
    }
}
