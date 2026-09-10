<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Plato;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Mostrar dashboard del administrador.
     */
    public function index(): View
    {
        // Total de pedidos
        $totalPedidos = Pedido::count();

        // Pedidos por estado
        $pedidosPendientes = Pedido::where(
            'estado',
            'pendiente'
        )->count();

        $pedidosPreparacion = Pedido::where(
            'estado',
            'en preparación'
        )->count();

        $pedidosListos = Pedido::where(
            'estado',
            'listo'
        )->count();

        $pedidosEntregados = Pedido::where(
            'estado',
            'entregado'
        )->count();

        // Pedidos que todavía no tienen pago
        $pagosPendientes = Pedido::where(
            'estado',
            'entregado'
        )
            ->whereDoesntHave('pago')
            ->count();

        // Total recaudado
        $totalPagado = Pedido::whereHas('pago')
            ->with('pago')
            ->get()
            ->sum(function ($pedido) {
                return $pedido->pago?->monto_pagado ?? 0;
            });

        // Platos disponibles
        $platosDisponibles = Plato::where(
            'disponibilidad',
            1
        )->count();

        // Últimos pedidos
        $ultimosPedidos = Pedido::with([
            'cliente.usuario',
            'usuario',
            'items.plato',
            'pago',
        ])
            ->orderByDesc('fecha')
            ->take(5)
            ->get();

        // Alertas de stock mínimo (HU-06)
        $alertasStock = \App\Models\MateriaPrima::whereColumn('stock_actual', '<=', 'stock_minimo')
            ->orderBy('stock_actual')
            ->get();

        // Estadísticas del Mes actual
        $inicioMes = now()->startOfMonth();
        $finMes = now()->endOfMonth();

        $pedidosMesQuery = Pedido::with(['items.plato', 'pago', 'cliente'])
            ->whereBetween('fecha', [$inicioMes, $finMes]);

        $pedidosCompletadosMes = (clone $pedidosMesQuery)->where('estado', 'entregado')->count();

        $ventasDelMes = (clone $pedidosMesQuery)->where('estado', 'entregado')->get()->sum(function ($pedido) {
            return $pedido->pago?->monto_pagado ?? $pedido->items->sum(fn($i) => $i->cantidad * $i->precio_unitario);
        });

        $clientesAtendidosMes = (clone $pedidosMesQuery)->where('estado', 'entregado')->pluck('id_cliente')->filter()->unique()->count();

        $platosMasVendidosMes = \Illuminate\Support\Facades\DB::table('item_pedido')
            ->join('plato', 'item_pedido.id_plato', '=', 'plato.id_plato')
            ->join('pedido', 'item_pedido.id_pedido', '=', 'pedido.id_pedido')
            ->where('pedido.estado', 'entregado')
            ->whereBetween('pedido.fecha', [$inicioMes, $finMes])
            ->select(
                'plato.nombre',
                \Illuminate\Support\Facades\DB::raw('SUM(item_pedido.cantidad) as total_cantidad'),
                \Illuminate\Support\Facades\DB::raw('SUM(item_pedido.cantidad * item_pedido.precio_unitario) as total_ventas')
            )
            ->groupBy('plato.id_plato', 'plato.nombre')
            ->orderByDesc('total_ventas')
            ->limit(10)
            ->get();

        return view(
            'admin.dashboard',
            compact(
                'totalPedidos',
                'pedidosPendientes',
                'pedidosPreparacion',
                'pedidosListos',
                'pedidosEntregados',
                'pagosPendientes',
                'totalPagado',
                'platosDisponibles',
                'ultimosPedidos',
                'alertasStock',
                'ventasDelMes',
                'pedidosCompletadosMes',
                'clientesAtendidosMes',
                'platosMasVendidosMes'
            )
        );
    }
}