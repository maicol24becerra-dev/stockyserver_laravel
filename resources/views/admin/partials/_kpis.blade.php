{{-- TARJETAS PRINCIPALES KPI DE ESTADÍSTICAS DEL MES (3 COLUMNAS) --}}
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
