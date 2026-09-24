<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cocina — El Cielo</title>
    <link rel="icon" href="{{ asset('images/logo-circle.png') }}?v={{ time() }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cocinero/cocinero-dashboard.css') }}?v={{ time() }}">
</head>
<body>

<!-- Session Destroyer Script -->
<script src="{{ asset('js/session-destroyer.js') }}"></script>

    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="brand">
            <img src="{{ asset('images/logo-circle.png') }}?v={{ time() }}" alt="El Cielo" class="brand-logo">
            <strong>El Cielo</strong>
            <span class="panel-tag">PANEL DE COCINA</span>
        </div>

        <nav>
            <a href="{{ route('cocinero.dashboard') }}" class="nav-link is-active">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="5" y="3" width="14" height="18" rx="2"/>
                    <line x1="9" y1="7" x2="15" y2="7"/>
                    <line x1="9" y1="11" x2="15" y2="11"/>
                    <line x1="9" y1="15" x2="13" y2="15"/>
                </svg>
                <span>Pedidos Activos</span>
            </a>
            <a href="{{ route('cocinero.historial') }}" class="nav-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
                <span>Historial</span>
            </a>
        </nav>
    </aside>

    {{-- MAIN WRAPPER --}}
    <div class="main-wrapper">

        {{-- TOPBAR --}}
        <header class="topbar">
            <div class="topbar-title-wrap">
                <span class="title-bar-accent"></span>
                <h1 class="topbar-title">Pedidos Activos</h1>
            </div>

            <div class="topbar-right">
                <div class="refresh-badge">
                    <span class="refresh-dot"></span>
                    Actualizando cada 15s
                </div>

                {{-- User Pill --}}
                <div class="topbar-user-pill" id="cocineroUserBtn">
                    <div class="user-avatar-badge">
                        {{ strtoupper(substr(auth()->user()->nombre ?? 'C', 0, 1)) }}
                    </div>
                    <div class="user-info">
                        <strong>{{ auth()->user()->nombre ?? 'cocinera' }}</strong>
                        <span>Cocinero</span>
                    </div>
                    <svg class="user-chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>

                    <div class="user-dropdown" id="cocineroDropdown">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                                </svg>
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="main-content">

            @if(session('success'))
                <div style="background: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: 600;">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="background: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: 600;">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            {{-- KPI CARDS --}}
            <div class="kpi-grid">
                <div class="kpi-card">
                    <div class="kpi-icon orange">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                    <div>
                        <span class="kpi-label">PENDIENTES</span>
                        <strong class="kpi-value">{{ $pendientes }}</strong>
                    </div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-icon amber">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 13.8a6 6 0 0 1 12 0"/><path d="M12 2v4"/><path d="M4 14h16v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-6z"/><line x1="12" y1="18" x2="12" y2="18.01"/>
                        </svg>
                    </div>
                    <div>
                        <span class="kpi-label">EN PREPARACIÓN</span>
                        <strong class="kpi-value">{{ $enPreparacion }}</strong>
                    </div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-icon red">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                    </div>
                    <div>
                        <span class="kpi-label">URGENTES</span>
                        <strong class="kpi-value">{{ $urgentes }}</strong>
                    </div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-icon purple">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="16" rx="3"/><polyline points="12 8 12 12 15 14"/>
                        </svg>
                    </div>
                    <div>
                        <span class="kpi-label">CON HORA ENTREGA</span>
                        <strong class="kpi-value">{{ $conHoraEntrega }}</strong>
                    </div>
                </div>
            </div>

            {{-- FILTROS --}}
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

            {{-- LISTADO DE PEDIDOS --}}
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

        </main>
    </div>

    <script>
        // Prevenir bfcache (Volver atrás/adelante en navegador)
        window.addEventListener('pageshow', function (event) {
            if (event.persisted || (window.performance && window.performance.navigation && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });

        // Refresco automático cada 15 segundos
        setTimeout(function () { window.location.reload(); }, 15000);

        // Dropdown de usuario
        const userBtn = document.getElementById('cocineroUserBtn');
        const dropdown = document.getElementById('cocineroDropdown');
        if (userBtn && dropdown) {
            userBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                dropdown.classList.toggle('show');
            });
            document.addEventListener('click', function () {
                dropdown.classList.remove('show');
            });
        }
    </script>

</body>
</html>
