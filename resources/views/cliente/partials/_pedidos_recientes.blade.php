{{-- ALERTAS Y MENSAJES --}}
@if(session('success'))
    <div style="background: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: 600;">
        ✓ {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div style="background: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: 600;">
        ⚠️ {{ $errors->first() }}
    </div>
@endif

{{-- HERO BANNER --}}
<div class="welcome-banner">
    <h2 class="welcome-title">
        ¡Bienvenido, {{ $usuario->nombre }}! 🌴
    </h2>
    <p class="welcome-subtitle">
        Disfruta de tu experiencia en el Centro Vacacional El Cielo
    </p>
</div>

{{-- SECCIÓN 1: PEDIDOS RECIENTES --}}
<div id="pedidos-seccion">
    <div class="section-title-wrap">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
            <line x1="16" y1="13" x2="8" y2="13"/>
            <line x1="16" y1="17" x2="8" y2="17"/>
        </svg>
        <span>Pedidos Recientes</span>
    </div>

    <div class="section-card">
        @if($pedidos->isEmpty())
            <div class="empty-icon-wrapper green">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                </svg>
            </div>
            <p class="empty-text">Aún no tienes pedidos registrados.</p>
            <a href="{{ route('cliente.menu') }}" class="btn-pill green">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
                Ver todos mis pedidos
            </a>
        @else
            <div class="orders-table-wrapper">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Pedido</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Total</th>
                            <th>Pago</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedidos as $pedido)
                            @php
                                $total = $pedido->items->sum(function ($item) {
                                    return $item->cantidad * $item->precio_unitario;
                                });
                                $estado = strtolower(trim($pedido->estado));
                            @endphp
                            <tr>
                                <td><strong>#{{ $pedido->id_pedido }}</strong></td>
                                <td>{{ $pedido->fecha?->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($estado === 'pendiente')
                                        <span class="badge badge-pendiente">Pendiente</span>
                                    @elseif($estado === 'en preparación')
                                        <span class="badge badge-preparacion">En preparación</span>
                                    @elseif($estado === 'listo')
                                        <span class="badge badge-listo">¡Listo!</span>
                                    @elseif($estado === 'entregado')
                                        <span class="badge badge-entregado">Entregado</span>
                                    @else
                                        <span class="badge badge-pendiente">{{ ucfirst($estado) }}</span>
                                    @endif
                                </td>
                                <td><strong>${{ number_format($total, 0, ',', '.') }}</strong></td>
                                <td>
                                    @if($pedido->pago)
                                        <span style="color: #16a34a; font-weight: 700;">✓ Pagado</span>
                                    @else
                                        <span style="color: #d97706; font-weight: 600;">Pendiente</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.pedidos.show', $pedido) }}" style="color: #0284c7; font-weight: 700; text-decoration: none; margin-right: 10px;">👁️ Ver</a>
                                    @if($estado === 'entregado')
                                        <form action="{{ route('cliente.repetir-pedido', $pedido) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            <button type="submit" onclick="return confirm('¿Deseas repetir este pedido?')" style="background: none; border: none; color: #16a34a; font-weight: 700; cursor: pointer; font-family: inherit;">
                                                🔄 Repetir
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
