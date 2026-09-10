@extends('layouts.admin')

@section('title', 'Panel de Supervisión')

@section('content')
<div class="page-header">
    <h1>📊 Panel de Supervisión en Tiempo Real</h1>
    <div>
        <button onclick="location.reload()" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/>
                <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"/>
            </svg>
            Actualizar
        </button>
        <small class="text-muted">Última actualización: {{ now()->format('H:i:s') }}</small>
    </div>
</div>

{{-- Auto-refresh cada 30 segundos --}}
<script>
    setTimeout(function() {
        location.reload();
    }, 30000);
</script>

{{-- Métricas principales --}}
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h3 class="mb-0">{{ $pedidosPorEstado->get('pendiente', 0) }}</h3>
                <p class="mb-0">⏳ Pedidos Pendientes</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h3 class="mb-0">{{ $pedidosPorEstado->get('en preparación', 0) }}</h3>
                <p class="mb-0">👨‍🍳 En Preparación</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h3 class="mb-0">{{ $pedidosPorEstado->get('listo', 0) }}</h3>
                <p class="mb-0">✅ Listos para Entregar</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h3 class="mb-0">{{ $stockCritico }}</h3>
                <p class="mb-0">⚠️ Alertas de Stock</p>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    {{-- Meseros Activos --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">👥 Meseros Activos</h5>
            </div>
            <div class="card-body">
                @if($meserosActivos->count())
                    <div class="list-group">
                        @foreach($meserosActivos as $mesero)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $mesero->nombre }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $mesero->role->nombre ?? 'Sin rol' }}</small>
                                </div>
                                <span class="badge badge-{{ $mesero->pedidos_pendientes > 3 ? 'danger' : ($mesero->pedidos_pendientes > 1 ? 'warning' : 'success') }} badge-pill">
                                    {{ $mesero->pedidos_pendientes }} pedidos
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No hay meseros con pedidos activos</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Estadísticas --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">⏱️ Métricas de Rendimiento</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th>Tiempo Promedio Preparación:</th>
                        <td><strong>{{ round($tiempoPromedio) }} minutos</strong></td>
                    </tr>
                    <tr>
                        <th>Pedidos Hoy:</th>
                        <td><strong>{{ array_sum($pedidosHoy->toArray()) }} pedidos</strong></td>
                    </tr>
                    <tr>
                        <th>Hora Pico:</th>
                        <td>
                            @if($pedidosHoy->count())
                                <strong>{{ $pedidosHoy->keys()->first() }}:00 - {{ ($pedidosHoy->keys()->first() + 1) }}:00</strong>
                            @else
                                <span class="text-muted">Sin datos</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    {{-- Platos más pedidos --}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">🍽️ Platos Más Pedidos Hoy</h5>
            </div>
            <div class="card-body">
                @if($platosMasPedidos->count())
                    <div class="row">
                        @foreach($platosMasPedidos as $plato)
                            <div class="col-md-2 text-center mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="text-primary">{{ $plato->total_pedidos }}</h4>
                                        <p class="mb-0">{{ $plato->nombre }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No hay pedidos registrados hoy</p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Pedidos en Tiempo Real --}}
<div class="card">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">🔴 LIVE: Pedidos en Tiempo Real</h5>
    </div>
    <div class="card-body">
        @if($pedidosEnTiempoReal->count())
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Hora</th>
                            <th>Cliente</th>
                            <th>Mesero</th>
                            <th>Items</th>
                            <th>Estado</th>
                            <th>Tiempo Transcurrido</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedidosEnTiempoReal as $pedido)
                            @php
                                $minutosTranscurridos = $pedido->fecha ? $pedido->fecha->diffInMinutes(now()) : 0;
                                $estadoClass = match(strtolower($pedido->estado)) {
                                    'pendiente' => 'warning',
                                    'en preparación' => 'info',
                                    'listo' => 'success',
                                    'entregado' => 'secondary',
                                    default => 'secondary'
                                };
                            @endphp
                            <tr class="{{ $minutosTranscurridos > 30 ? 'table-danger' : '' }}">
                                <td><strong>#{{ $pedido->id_pedido }}</strong></td>
                                <td>{{ $pedido->fecha?->format('H:i') }}</td>
                                <td>{{ $pedido->cliente?->usuario?->nombre ?? 'Sin cliente' }}</td>
                                <td>{{ $pedido->usuario?->nombre ?? '-' }}</td>
                                <td>
                                    <small>
                                        @foreach($pedido->items->take(2) as $item)
                                            {{ $item->cantidad }}x {{ $item->plato?->nombre }}@if(!$loop->last), @endif
                                        @endforeach
                                        @if($pedido->items->count() > 2)
                                            <br><span class="text-muted">+{{ $pedido->items->count() - 2 }} más</span>
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $estadoClass }}">
                                        {{ ucfirst($pedido->estado) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="{{ $minutosTranscurridos > 30 ? 'text-danger font-weight-bold' : '' }}">
                                        {{ $minutosTranscurridos }} min
                                        @if($minutosTranscurridos > 30)
                                            ⚠️
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.pedidos.show', $pedido) }}" class="btn btn-sm btn-outline-primary">
                                        Ver
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted">No hay pedidos activos en este momento</p>
        @endif
    </div>
</div>

<style>
.table-danger {
    animation: pulse-red 2s infinite;
}

@keyframes pulse-red {
    0% { background-color: rgba(220, 53, 69, 0.1); }
    50% { background-color: rgba(220, 53, 69, 0.3); }
    100% { background-color: rgba(220, 53, 69, 0.1); }
}
</style>
@endsection