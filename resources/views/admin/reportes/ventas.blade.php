@extends('layouts.admin')

@section('title', 'Panel de Administrador - El Cielo')
@section('page_title', 'Reportes de Ventas')

@section('content')

<!-- CARD DE FILTROS DEL REPORTE -->
<div class="admin-card">
    <div class="filter-card-header">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
        </svg>
        FILTROS DEL REPORTE
    </div>

    <form method="GET" action="{{ route('admin.reportes.ventas') }}">
        <div class="filter-grid">
            <div class="filter-group">
                <label class="filter-label">Período</label>
                <select name="periodo" class="filter-select" onchange="this.form.submit()">
                    <option value="mes_actual" {{ request('periodo', 'mes_actual') === 'mes_actual' || request('periodo') === 'este_mes' ? 'selected' : '' }}>Mes actual</option>
                    <option value="hoy" {{ request('periodo') === 'hoy' ? 'selected' : '' }}>Hoy</option>
                    <option value="ayer" {{ request('periodo') === 'ayer' ? 'selected' : '' }}>Ayer</option>
                    <option value="esta_semana" {{ request('periodo') === 'esta_semana' ? 'selected' : '' }}>Esta semana</option>
                    <option value="este_ano" {{ request('periodo') === 'este_ano' ? 'selected' : '' }}>Este año</option>
                    <option value="todos" {{ request('periodo') === 'todos' ? 'selected' : '' }}>Todos</option>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">Método de pago</label>
                <select name="metodo_pago" class="filter-select">
                    <option value="Todos" {{ request('metodo_pago') === 'Todos' || !request('metodo_pago') ? 'selected' : '' }}>Todos</option>
                    <option value="efectivo" {{ request('metodo_pago') === 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                    <option value="tarjeta" {{ request('metodo_pago') === 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                    <option value="transferencia" {{ request('metodo_pago') === 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                    <option value="nequi" {{ request('metodo_pago') === 'nequi' ? 'selected' : '' }}>Nequi</option>
                    <option value="daviplata" {{ request('metodo_pago') === 'daviplata' ? 'selected' : '' }}>Daviplata</option>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">Categoría</label>
                <select name="categoria" class="filter-select">
                    <option value="Todas" {{ request('categoria') === 'Todas' || !request('categoria') ? 'selected' : '' }}>Todas</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->nombre }}" {{ request('categoria') == $cat->nombre ? 'selected' : '' }}>
                            {{ $cat->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">Platillo</label>
                <select name="id_plato" class="filter-select">
                    <option value="Todos" {{ request('id_plato') === 'Todos' || !request('id_plato') ? 'selected' : '' }}>Todos</option>
                    @foreach($platos as $plato)
                        <option value="{{ $plato->id_plato }}" {{ request('id_plato') == $plato->id_plato ? 'selected' : '' }}>
                            {{ $plato->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-aplicar">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                    Aplicar
                </button>
                <a href="{{ route('admin.reportes.ventas') }}" class="btn-limpiar">
                    ✕ Limpiar
                </a>
            </div>
        </div>
    </form>
</div>

<!-- TARJETAS DE MÉTRICAS KPI (4 COLUMNAS) -->
<div class="kpi-grid">
    <!-- Total Ingresos -->
    <div class="kpi-card">
        <div class="kpi-icon-box cyan">
            $
        </div>
        <div class="kpi-content">
            <span class="kpi-label">TOTAL INGRESOS</span>
            <span class="kpi-value">${{ number_format($totalVentas, 2) }}</span>
        </div>
    </div>

    <!-- Pedidos Pagados -->
    <div class="kpi-card">
        <div class="kpi-icon-box purple">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <path d="M16 10a4 4 0 0 1-8 0"></path>
            </svg>
        </div>
        <div class="kpi-content">
            <span class="kpi-label">PEDIDOS PAGADOS</span>
            <span class="kpi-value">{{ $pedidosPagados }}</span>
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
            <span class="kpi-value">{{ $clientesAtendidos }}</span>
        </div>
    </div>

    <!-- Ticket Promedio -->
    <div class="kpi-card">
        <div class="kpi-icon-box emerald">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1-2-1z"></path>
                <line x1="8" y1="6" x2="16" y2="6"></line>
                <line x1="8" y1="10" x2="16" y2="10"></line>
                <line x1="8" y1="14" x2="12" y2="14"></line>
            </svg>
        </div>
        <div class="kpi-content">
            <span class="kpi-label">TICKET PROMEDIO</span>
            <span class="kpi-value">${{ number_format($ticketPromedio, 2) }}</span>
        </div>
    </div>
</div>

<!-- GRÁFICOS (EVOLUCIÓN Y MÉTODOS DE PAGO) -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 28px;">
    <!-- Evolución de Ventas (Líneas) -->
    <div class="chart-card" style="margin-bottom: 0;">
        <div class="chart-header">
            <span class="title-accent-bar"></span>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0d9488" stroke-width="2.5">
                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                <polyline points="17 6 23 6 23 12"></polyline>
            </svg>
            <h3 class="chart-title">Evolución de Ventas</h3>
        </div>
        <div class="chart-container" style="height: 280px;">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Distribución Método de Pago (Dona) -->
    <div class="chart-card" style="margin-bottom: 0;">
        <div class="chart-header">
            <span class="title-accent-bar"></span>
            <h3 class="chart-title" style="font-size: 1.05rem;">Ventas por Método</h3>
        </div>
        <div class="chart-container" style="height: 280px;">
            <canvas id="payMethodChart"></canvas>
        </div>
    </div>
</div>

<!-- RANKING DE PLATILLOS (CON BOTÓN DE EXPORTAR PDF) -->
<div class="admin-card">
    <div class="admin-card-header">
        <div style="display: flex; align-items: center; gap: 10px;">
            <span style="color: #f59e0b; font-size: 1.2rem;">★</span>
            <h3 style="margin: 0; font-size: 1.2rem; font-weight: 800; color: var(--admin-text-dark);">Ranking de Platillos</h3>
        </div>
        <a href="{{ route('admin.reportes.ventas.pdf', request()->query()) }}" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; background-color: #d97706; color: #ffffff; padding: 10px 18px; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 0.9rem; transition: background 0.2s;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
            </svg>
            Exportar PDF
        </a>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr style="background-color: #f1f7f4;">
                    <th style="width: 50px; color: #0d9488;">#</th>
                    <th style="width: 80px; color: #0d9488;">IMAGEN</th>
                    <th style="color: #0d9488;">PLATILLO</th>
                    <th style="color: #0d9488;">CATEGORÍA</th>
                    <th style="color: #0d9488;">UNIDADES</th>
                    <th style="color: #0d9488; text-align: right;">INGRESOS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rankingPlatillos as $idx => $plato)
                    <tr>
                        <td>
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; background: #f59e0b; color: white; border-radius: 50%; font-size: 0.75rem; font-weight: 800;">
                                {{ $idx + 1 }}
                            </span>
                        </td>
                        <td>
                            @if($plato->imagen)
                                <img src="{{ asset('storage/' . $plato->imagen) }}" alt="{{ $plato->nombre }}" style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover;">
                            @else
                                <div style="width: 40px; height: 40px; border-radius: 8px; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">🍽️</div>
                            @endif
                        </td>
                        <td><strong style="color: var(--admin-text-dark);">{{ strtolower($plato->nombre) }}</strong></td>
                        <td>
                            <span class="badge" style="background-color: #e0f2fe; color: #0369a1; text-transform: lowercase;">
                                {{ $plato->categoria_nombre ?? $plato->plato_categoria_str ?? 'general' }}
                            </span>
                        </td>
                        <td style="font-weight: 700; color: #009640;">{{ $plato->total_unidades }} uds.</td>
                        <td style="text-align: right; font-weight: 900; color: var(--admin-text-dark);">${{ number_format($plato->total_ingresos, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #94a3b8; padding: 36px 0; font-weight: 600;">
                            No hay platillos vendidos registrados en el período seleccionado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- DESGLOSE POR MÉTODO DE PAGO -->
<div class="admin-card">
    <div class="admin-card-header">
        <div style="display: flex; align-items: center; gap: 10px;">
            <span class="title-accent-bar"></span>
            <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: var(--admin-text-dark);">Desglose por Método de Pago</h3>
        </div>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr style="background-color: #f1f7f4;">
                    <th style="color: #0d9488;">MÉTODO</th>
                    <th style="color: #0d9488;">PEDIDOS</th>
                    <th style="color: #0d9488;">TOTAL RECAUDADO</th>
                    <th style="color: #0d9488;">% DEL TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ventasPorMetodo as $metodo)
                    <tr>
                        <td>
                            <strong style="text-transform: capitalize; color: var(--admin-text-dark);">
                                💳 {{ $metodo->metodo_pago }}
                            </strong>
                        </td>
                        <td style="font-weight: 600; color: #475569;">{{ $metodo->total_pedidos }}</td>
                        <td style="font-weight: 800; color: #009640;">${{ number_format($metodo->total_monto, 2) }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="flex: 1; background: #e2e8f0; height: 10px; border-radius: 5px; overflow: hidden;">
                                    <div style="width: {{ $metodo->porcentaje }}%; background: #009640; height: 100%; border-radius: 5px;"></div>
                                </div>
                                <span style="font-weight: 800; font-size: 0.85rem; color: #475569;">{{ $metodo->porcentaje }}%</span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #94a3b8; padding: 36px 0; font-weight: 600;">
                            No hay pagos registrados en el período seleccionado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- DETALLE COMPLETO DE PEDIDOS -->
<div class="admin-card">
    <div class="admin-card-header">
        <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800;">Detalle de Ventas</h3>
        <a href="{{ route('admin.reportes.ventas.exportar', request()->query()) }}" class="btn-aplicar" style="text-decoration: none; padding: 8px 16px; font-size: 0.85rem;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            Exportar CSV
        </a>
    </div>

    @if($pedidos->count())
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Atendido por</th>
                        <th>Estado</th>
                        <th>Método Pago</th>
                        <th style="text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedidos as $pedido)
                        <tr>
                            <td><strong>#{{ $pedido->id_pedido }}</strong></td>
                            <td>{{ $pedido->fecha ? $pedido->fecha->format('d/m/Y H:i') : '-' }}</td>
                            <td>{{ $pedido->cliente?->usuario?->nombre ?? 'Cliente general' }}</td>
                            <td>{{ $pedido->usuario?->nombre ?? 'Mesero' }}</td>
                            <td>
                                @if($pedido->estado === 'entregado')
                                    <span class="badge badge-success">Entregado</span>
                                @elseif($pedido->estado === 'cancelado')
                                    <span class="badge badge-danger">Cancelado</span>
                                @else
                                    <span class="badge badge-warning">{{ ucfirst($pedido->estado) }}</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $pedido->pago ? ucfirst($pedido->pago->metodo_pago) : 'Pendiente' }}</strong>
                            </td>
                            <td style="text-align: right;">
                                <a href="{{ route('admin.pedidos.show', $pedido) }}" style="color: #0d9488; font-weight: 700; text-decoration: none;">Ver Detalle →</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top: 20px;">
            {{ $pedidos->appends(request()->query())->links() }}
        </div>
    @else
        <p style="text-align: center; color: #94a3b8; padding: 40px 0; margin: 0; font-weight: 600;">
            No se encontraron ventas o pedidos con los filtros seleccionados.
        </p>
    @endif
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gráfico de líneas (Evolución de Ventas)
        const ctxSales = document.getElementById('salesChart').getContext('2d');
        const labels = @json($chartLabels);
        const ventasData = @json($chartVentasData);
        const pedidosData = @json($chartPedidosData);

        new Chart(ctxSales, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Ventas ($)',
                        data: ventasData,
                        borderColor: '#00c2e0',
                        backgroundColor: 'rgba(0, 194, 224, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#00c2e0',
                        tension: 0.3,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Pedidos',
                        data: pedidosData,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#10b981',
                        tension: 0.3,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', align: 'end' }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { type: 'linear', position: 'left', ticks: { callback: v => '$' + v } },
                    y1: { type: 'linear', position: 'right', grid: { drawOnChartArea: false } }
                }
            }
        });

        // Gráfico de Dona (Ventas por Método de Pago)
        const ctxPay = document.getElementById('payMethodChart').getContext('2d');
        const metodosData = @json($ventasPorMetodo);
        const payLabels = metodosData.map(m => m.metodo_pago);
        const payValues = metodosData.map(m => m.total_monto);

        new Chart(ctxPay, {
            type: 'doughnut',
            data: {
                labels: payLabels.length ? payLabels : ['Sin datos'],
                datasets: [{
                    data: payValues.length ? payValues : [1],
                    backgroundColor: ['#00c2e0', '#8b5cf6', '#10b981', '#f59e0b', '#ef4444'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    });
</script>
@endpush
