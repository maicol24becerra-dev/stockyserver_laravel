@extends('layouts.admin')

@section('title', 'Panel de Administrador - El Cielo')
@section('page_title', 'Control de Pedidos')

@section('content')

<!-- BARRA DE ACCIÓN (NUEVO PEDIDO) -->
@if(auth()->user()->role?->nombre === 'Administrador' || auth()->user()->role?->nombre === 'Mesero')
    <div style="display: flex; justify-content: flex-end; margin-bottom: 20px;">
        <a href="{{ route('admin.pedidos.create') }}" class="btn-aplicar" style="text-decoration: none;">
            + Nuevo Pedido
        </a>
    </div>
@endif

<!-- PESTAÑAS DE FILTRO POR ESTADO -->
<div class="pedidos-tab-bar">
    <a href="{{ route('admin.pedidos.index', ['estado' => 'todos']) }}" class="tab-btn {{ $estadoFiltro === 'todos' ? 'active' : '' }}">
        Todos ({{ $countTodos }})
    </a>
    <a href="{{ route('admin.pedidos.index', ['estado' => 'pendiente']) }}" class="tab-btn tab-pendiente {{ $estadoFiltro === 'pendiente' ? 'active' : '' }}">
        Pendientes {{ $countPendientes > 0 ? '('.$countPendientes.')' : '' }}
    </a>
    <a href="{{ route('admin.pedidos.index', ['estado' => 'en preparación']) }}" class="tab-btn tab-preparacion {{ $estadoFiltro === 'en preparación' ? 'active' : '' }}">
        En preparación {{ $countPreparacion > 0 ? '('.$countPreparacion.')' : '' }}
    </a>
    <a href="{{ route('admin.pedidos.index', ['estado' => 'entregado']) }}" class="tab-btn tab-entregado {{ $estadoFiltro === 'entregado' ? 'active' : '' }}">
        Entregados {{ $countEntregados > 0 ? '('.$countEntregados.')' : '' }}
    </a>
    <a href="{{ route('admin.pedidos.index', ['estado' => 'pagados']) }}" class="tab-btn tab-pagado {{ $estadoFiltro === 'pagados' ? 'active' : '' }}">
        Pagados {{ $countPagados > 0 ? '('.$countPagados.')' : '' }}
    </a>
    <a href="{{ route('admin.pedidos.index', ['estado' => 'cancelado']) }}" class="tab-btn tab-cancelado {{ $estadoFiltro === 'cancelado' ? 'active' : '' }}">
        Cancelados {{ $countCancelados > 0 ? '('.$countCancelados.')' : '' }}
    </a>
</div>

<!-- LISTADO DE TARJETAS DE PEDIDOS -->
<div class="order-cards-list">
    @forelse($pedidos as $pedido)
        @php
            $estadoStr = strtolower(trim($pedido->estado));
            $totalMonto = $pedido->pago ? $pedido->pago->monto_pagado : $pedido->items->sum(fn($i) => $i->cantidad * $i->precio_unitario);
        @endphp
        <div class="order-card">
            <!-- Header de Tarjeta -->
            <div class="order-card-header">
                <div class="order-meta-left">
                    <h3 class="order-id">Pedido #{{ $pedido->id_pedido }}</h3>
                    <span class="order-meta-item">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                        {{ $pedido->fecha ? $pedido->fecha->format('d/m/Y H:i') : '-' }}
                    </span>
                    <span class="order-meta-item">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        {{ $pedido->usuario?->nombre ?? $pedido->cliente?->usuario?->nombre ?? 'mesero' }}
                    </span>
                </div>

                <div class="order-meta-right">
                    @if($pedido->pago)
                        <span class="badge badge-success">Pagado</span>
                    @elseif($estadoStr === 'cancelado')
                        <span class="badge badge-danger">Cancelado</span>
                    @elseif($estadoStr === 'entregado')
                        <span class="badge badge-success">Entregado</span>
                    @elseif($estadoStr === 'en preparación')
                        <span class="badge" style="background-color: #e0f2fe; color: #0369a1;">En preparación</span>
                    @elseif($estadoStr === 'listo')
                        <span class="badge" style="background-color: #d1fae5; color: #047857;">Listo</span>
                    @else
                        <span class="badge badge-warning">Pendiente</span>
                    @endif
                </div>
            </div>

            <!-- Ítems del Pedido -->
            <div class="order-card-body">
                @forelse($pedido->items as $item)
                    <div class="order-item-row">
                        <div class="item-name">
                            <strong>{{ strtolower($item->plato?->nombre ?? 'plato') }}</strong>
                        </div>
                        <div class="item-qty">
                            {{ $item->cantidad }} u.
                        </div>
                        <div class="item-subtotal">
                            ${{ number_format($item->cantidad * $item->precio_unitario, 2) }}
                        </div>
                    </div>
                @empty
                    <div style="font-size: 0.85rem; color: #94a3b8;">Sin ítems agregados</div>
                @endforelse
            </div>

            <!-- Footer de Tarjeta (Total y Acciones) -->
            <div class="order-card-footer">
                <div>
                    <a href="{{ route('admin.pedidos.show', $pedido) }}" class="btn-link-action">Ver detalle →</a>
                    @if(!$pedido->pago && $estadoStr !== 'cancelado')
                        <a href="{{ route('admin.pedidos.pago.create', $pedido) }}" class="btn-link-action" style="color: #009640;">$ Registrar Pago</a>
                    @endif
                </div>
                <div class="order-total-block">
                    <span>Total:</span>
                    <strong class="order-total-val">${{ number_format($totalMonto, 2) }}</strong>
                </div>
            </div>
        </div>
    @empty
        <div class="admin-card" style="text-align: center; color: #94a3b8; padding: 48px 0; margin: 0;">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 12px; color: #cbd5e1;">
                <rect x="5" y="3" width="14" height="18" rx="2"/><line x1="9" y1="7" x2="15" y2="7"/><line x1="9" y1="11" x2="15" y2="11"/>
            </svg>
            <h4 style="margin: 0 0 6px 0; color: var(--admin-text-dark); font-weight: 700;">No hay pedidos registrados</h4>
            <p style="margin: 0; font-size: 0.9rem;">Cuando existan pedidos en esta categoría aparecerán en este panel.</p>
        </div>
    @endforelse
</div>

@endsection