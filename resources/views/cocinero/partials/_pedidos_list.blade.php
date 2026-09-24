{{-- FILTROS DE ESTADO --}}
<div class="filter-bar">
    <a href="{{ route('cocinero.dashboard') }}" class="filter-tab {{ !request('estado') ? 'active' : '' }}">
        Todos ({{ $pedidos->count() }})
    </a>
    <a href="{{ route('cocinero.dashboard', ['estado' => 'pendiente']) }}" class="filter-tab {{ request('estado') === 'pendiente' ? 'active' : '' }}">
        Pendientes
    </a>
    <a href="{{ route('cocinero.dashboard', ['estado' => 'en preparación']) }}" class="filter-tab {{ request('estado') === 'en preparación' ? 'active' : '' }}">
        En preparación
    </a>
    <a href="{{ route('cocinero.dashboard', ['estado' => 'urgente']) }}" class="filter-tab {{ request('estado') === 'urgente' ? 'active' : '' }}">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 2px;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        Urgentes
    </a>
</div>

{{-- LISTADO DE PEDIDOS KDS --}}
<div class="orders-list">
    @forelse($pedidos as $pedido)
        @php
            $prioridad = $pedido->prioridad ?? 'normal';
            $estado    = strtolower(trim($pedido->estado));
        @endphp

        <div class="order-card {{ $prioridad === 'urgente' ? 'urgente' : '' }}">
            {{-- CABECERA --}}
            <div class="order-card-header">
                <div class="order-meta">
                    <h3 class="order-id">Pedido #{{ $pedido->id_pedido }}</h3>
                    <span style="color: #64748b;">
                        🕐 {{ $pedido->fecha ? $pedido->fecha->format('d/m H:i') : '-' }}
                    </span>
                    <span style="color: #64748b;">
                        👤 {{ $pedido->cliente?->usuario?->nombre ?? $pedido->usuario?->nombre ?? 'Sin cliente' }}
                    </span>
                </div>

                @if($estado === 'pendiente')
                    <span class="badge badge-pendiente">Pendiente</span>
                @elseif($estado === 'en preparación')
                    <span class="badge badge-preparacion">En preparación</span>
                @elseif($prioridad === 'urgente')
                    <span class="badge badge-urgente">🚨 Urgente</span>
                @else
                    <span class="badge badge-listo">{{ ucfirst($estado) }}</span>
                @endif
            </div>

            {{-- ÍTEMS --}}
            <div class="order-card-body">
                @forelse($pedido->items as $item)
                    <div class="order-item-row">
                        <span class="item-qty">{{ $item->cantidad }}x</span>
                        <span class="item-name">{{ $item->plato?->nombre ?? 'Plato no encontrado' }}</span>
                    </div>
                    @if($item->notas_especiales)
                        <div class="notes-block">
                            <strong>⚠️ Instrucciones especiales</strong>
                            {{ $item->notas_especiales }}
                        </div>
                    @endif
                @empty
                    <p style="color: #94a3b8; font-size: 0.85rem;">Sin ítems en este pedido</p>
                @endforelse
            </div>

            {{-- FOOTER CON ACCIONES --}}
            <div class="order-card-footer">
                <a href="{{ route('admin.pedidos.show', $pedido) }}" class="btn-ver">Ver detalle →</a>

                @if($estado === 'pendiente')
                    <form method="POST" action="{{ route('admin.pedidos.estado', $pedido) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="estado" value="en preparación">
                        <button type="submit" class="btn-listo" style="background: #d97706;">
                            🍳 Iniciar preparación
                        </button>
                    </form>
                @elseif($estado === 'en preparación')
                    <form method="POST" action="{{ route('admin.pedidos.estado', $pedido) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="estado" value="listo">
                        <button type="submit" class="btn-listo">
                            ✓ Marcar como listo
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="empty-state">
            <div class="empty-icon-check">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <h3 class="empty-title">No hay pedidos pendientes en este momento.</h3>
            <p class="empty-subtitle">La pantalla se actualiza automáticamente.</p>
        </div>
    @endforelse
</div>
