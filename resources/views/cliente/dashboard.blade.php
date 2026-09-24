<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Panel — El Cielo</title>
    <link rel="icon" href="{{ asset('images/logo-circle.png') }}?v={{ time() }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cliente/cliente-dashboard.css') }}?v={{ time() }}">
</head>
<body>

<!-- Session Destroyer Script -->
<script src="{{ asset('js/session-destroyer.js') }}"></script>

    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="brand">
            <img src="{{ asset('images/logo-circle.png') }}?v={{ time() }}" alt="El Cielo" class="brand-logo">
            <strong>El Cielo</strong>
            <span class="brand-subtitle">CENTRO VACACIONAL<br>Y RECREACIONAL</span>
        </div>

        <nav>
            <a href="{{ route('cliente.dashboard') }}" class="nav-link is-active">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                <span>Inicio</span>
            </a>
            <a href="{{ route('cliente.menu') }}" class="nav-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8h1a4 4 0 0 1 0 8h-1"/>
                    <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/>
                    <line x1="6" y1="1" x2="6" y2="4"/>
                    <line x1="10" y1="1" x2="10" y2="4"/>
                    <line x1="14" y1="1" x2="14" y2="4"/>
                </svg>
                <span>Explorar Menú</span>
            </a>
            <a href="{{ route('cliente.pedidos') }}" class="nav-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
                <span>Mis Pedidos</span>
            </a>
            <a href="{{ route('cliente.perfil') }}" class="nav-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                <span>Mi Perfil</span>
            </a>
        </nav>
    </aside>

    {{-- MAIN WRAPPER --}}
    <div class="main-wrapper">

        {{-- TOPBAR --}}
        <header class="topbar">
            <div class="topbar-title-wrap">
                <span class="title-bar-accent"></span>
                <h1 class="topbar-title">Inicio</h1>
            </div>

            <div class="topbar-right">
                {{-- User Pill --}}
                <div class="topbar-user-pill" id="clienteUserBtn">
                    <div class="user-avatar-badge">
                        {{ strtoupper(substr($usuario->nombre ?? 'M', 0, 1)) }}
                    </div>
                    <div class="user-info">
                        <strong>{{ $usuario->nombre ?? 'Cliente' }}</strong>
                        <span>Cliente</span>
                    </div>
                    <svg class="user-chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>

                    <div class="user-dropdown" id="clienteDropdown">
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

            {{-- SECCIÓN 2: PROMOCIONES Y OFERTAS DEL DÍA --}}
            <div>
                <div class="section-title-wrap">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1.5">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                    <span>Promociones y Ofertas del Día</span>
                </div>

                <div class="section-card">
                    <div class="empty-icon-wrapper cyan">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                            <line x1="7" y1="7" x2="7.01" y2="7"/>
                        </svg>
                    </div>
                    <p class="empty-text">No hay promociones activas en este momento.</p>
                    <a href="{{ route('cliente.menu') }}" class="btn-pill cyan">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>
                        </svg>
                        Ver menú
                    </a>
                </div>
            </div>

        </main>
    </div>

    {{-- BOTÓN FLOTANTE Y MODAL DE CHATBOT --}}
    @include('cliente.partials.chatbot')

    <script>
        // Prevenir bfcache (Volver atrás/adelante en navegador)
        window.addEventListener('pageshow', function (event) {
            if (event.persisted || (window.performance && window.performance.navigation && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });

        // Dropdown de usuario
        const userBtn = document.getElementById('clienteUserBtn');
        const dropdown = document.getElementById('clienteDropdown');
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
