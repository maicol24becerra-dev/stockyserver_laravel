@extends('layouts.admin')

@section('title', 'Panel de Administrador - El Cielo')
@section('page_title', 'Estadísticas del Mes')

@section('content')

<!-- TARJETAS PRINCIPALES KPI DE ESTADÍSTICAS DEL MES (3 COLUMNAS) -->
<div class="kpi-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom: 32px;">
    <!-- Ventas del Mes -->
    <div class="kpi-card">
        <div class="kpi-icon-box cyan">
            $
        </div>
        <div class="kpi-content">
            <span class="kpi-label">VENTAS DEL MES</span>
            <span class="kpi-value">${{ number_format($ventasDelMes, 2) }}</span>
        </div>
    </div>

    <!-- Pedidos Completados -->
    <div class="kpi-card">
        <div class="kpi-icon-box purple">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <path d="M16 10a4 4 0 0 1-8 0"></path>
            </svg>
        </div>
        <div class="kpi-content">
            <span class="kpi-label">PEDIDOS COMPLETADOS</span>
            <span class="kpi-value">{{ $pedidosCompletadosMes }}</span>
        </div>
    </div>

    <!-- Clientes Atendidos -->
    <div class="kpi-card">
        <div class="kpi-icon-box salmon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
        </div>
        <div class="kpi-content">
            <span class="kpi-label">CLIENTES ATENDIDOS</span>
            <span class="kpi-value">{{ $clientesAtendidosMes }}</span>
        </div>
    </div>
</div>

<!-- SECCIÓN: PLATOS MÁS VENDIDOS -->
<div class="admin-card">
    <div class="admin-card-header">
        <div style="display: flex; align-items: center; gap: 10px;">
            <span class="title-accent-bar"></span>
            <h3 style="margin: 0; font-size: 1.2rem; font-weight: 800; color: var(--admin-text-dark);">Platos Más Vendidos</h3>
        </div>
        <a href="{{ route('admin.reportes.ventas') }}" style="color: #0d9488; font-weight: 700; text-decoration: none; font-size: 0.9rem;">
            Ver reporte completo →
        </a>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr style="background-color: #f1f7f4;">
                    <th style="color: #0d9488;">PLATO</th>
                    <th style="color: #0d9488;">CANTIDAD VENDIDA</th>
                    <th style="color: #0d9488;">INGRESOS GENERADOS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($platosMasVendidosMes as $plato)
                    <tr>
                        <td><strong style="color: var(--admin-text-dark);">{{ strtolower($plato->nombre) }}</strong></td>
                        <td style="font-weight: 600; color: #475569;">{{ $plato->total_cantidad }} uds.</td>
                        <td style="font-weight: 800; color: #009640;">${{ number_format($plato->total_ventas, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: #94a3b8; padding: 36px 0; font-weight: 600;">
                            No hay platos vendidos registrados en este mes.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- SECCIÓN SECUNDARIA: ALERTAS Y ACCESOS -->
@if($alertasStock->count() > 0)
<div class="admin-card" style="border-left: 4px solid #f59e0b; background-color: #fffbeb;">
    <div class="admin-card-header">
        <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #b45309;">
            ⚠️ Alertas de Stock Mínimo ({{ $alertasStock->count() }})
        </h3>
        <a href="{{ route('admin.inventario.index') }}" style="color: #b45309; font-weight: 700; text-decoration: none;">
            Gestionar Inventario →
        </a>
    </div>
    
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="color: #b45309;">Materia Prima</th>
                    <th style="color: #b45309;">Stock Disponible</th>
                    <th style="color: #b45309;">Stock Mínimo</th>
                    <th style="color: #b45309;">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($alertasStock as $materia)
                    <tr>
                        <td><strong>{{ $materia->nombre }}</strong></td>
                        <td>{{ number_format($materia->stock_actual, 2) }} {{ $materia->unidad_medida }}</td>
                        <td>{{ number_format($materia->stock_minimo, 2) }} {{ $materia->unidad_medida }}</td>
                        <td>
                            @if($materia->stock_actual == 0)
                                <span class="badge badge-danger">🔴 AGOTADO</span>
                            @else
                                <span class="badge badge-warning">🟡 CRÍTICO</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
