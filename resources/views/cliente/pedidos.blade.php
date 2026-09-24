<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pedidos — El Cielo</title>
    <link rel="icon" href="{{ asset('images/logo-circle.png') }}?v={{ time() }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cliente/cliente-dashboard.css') }}?v={{ time() }}">
</head>
<body>

    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="brand">
            <img src="{{ asset('images/logo-circle.png') }}?v={{ time() }}" alt="El Cielo" class="brand-logo">
            <strong>El Cielo</strong>
            <span class="brand-subtitle">CENTRO VACACIONAL<br>Y RECREACIONAL</span>
        </div>

        <nav>
            <a href="{{ route('cliente.dashboard') }}" class="nav-link">
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
            <a href="{{ route('cliente.pedidos') }}" class="nav-link is-active">
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
                <h1 class="topbar-title">Mis Pedidos</h1>
            </div>

            <div class="topbar-right">
                {{-- User Pill --}}
                <div class="topbar-user-pill" id="clienteUserBtn">
                    <div class="user-avatar-badge">
                        {{ strtoupper(substr($usuario->nombre ?? 'M', 0, 1)) }}
                    </div>
                    <div class="user-info">
                        <strong>{{ $usuario->nombre ?? 'Maicol' }}</strong>
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

            <div class="section-card" style="text-align: left; padding: 24px;">
                <div class="section-title-wrap" style="margin-top: 0; margin-bottom: 24px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                    <span>Mis Pedidos</span>
                </div>

                @if($pedidos->isEmpty())
                    <div style="text-align: center; padding: 48px 20px;">
                        <div class="empty-icon-wrapper green" style="background: #e6f4ea; color: #16a34a; width: 56px; height: 56px; border-radius: 14px;">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                                <line x1="12" y1="22.08" x2="12" y2="12"/>
                            </svg>
                        </div>
                        <p style="font-size: 0.95rem; font-weight: 600; color: #94a3b8; margin-top: 16px;">
                            No tienes pedidos registrados aún.
                        </p>
                    </div>
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

        </main>
    </div>

    {{-- BOTÓN FLOTANTE Y MODAL DE CHATBOT --}}
    @include('cliente.partials.chatbot')

    <script>
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
