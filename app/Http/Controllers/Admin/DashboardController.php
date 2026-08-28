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
                'ultimosPedidos'
            )
        );
    }
}