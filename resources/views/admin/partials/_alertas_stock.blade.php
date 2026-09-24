{{-- SECCIÓN SECUNDARIA: ALERTAS DE STOCK MÍNIMO --}}
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
