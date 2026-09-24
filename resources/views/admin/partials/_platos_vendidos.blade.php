{{-- SECCIÓN: PLATOS MÁS VENDIDOS --}}
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
