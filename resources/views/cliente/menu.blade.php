<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explorar Menú — El Cielo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cliente/cliente-dashboard.css') }}?v={{ time() }}">
</head>
<body>

    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="brand">
            <img src="{{ asset('elcielo/assets/img/hero.png') }}?v={{ time() }}" alt="El Cielo" class="brand-logo">
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
            <a href="{{ route('cliente.menu') }}" class="nav-link is-active">
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
                <h1 class="topbar-title">Explorar Menú</h1>
            </div>

            <div class="topbar-right">
                {{-- User Pill --}}
                <div class="topbar-user-pill" id="clienteUserBtn">
                    <div class="user-avatar-badge">
                        {{ strtoupper(substr(auth()->user()->nombre ?? 'M', 0, 1)) }}
                    </div>
                    <div class="user-info">
                        <strong>{{ auth()->user()->nombre ?? 'Maicol' }}</strong>
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

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h2 style="font-size: 1.25rem; font-weight: 800; color: #064e3b; margin: 0;">
                    Nuestro Menú
                </h2>
                <span style="font-size: 0.88rem; font-weight: 600; color: #64748b;">
                    {{ $platos->total() }} plato{{ $platos->total() === 1 ? '' : 's' }} disponible{{ $platos->total() === 1 ? '' : 's' }}
                </span>
            </div>

            @if($platos->count())
                <div class="menu-cards-grid">
                    @foreach($platos as $plato)
                        @php
                            $categoriaNombre = strtolower(trim($plato->categoriaRelacion?->nombre ?? $plato->categoria ?? 'carnes'));
                        @endphp
                        <div class="menu-dish-card">
                            <div class="dish-image-wrapper">
                                @if($plato->imagen)
                                    <img src="{{ asset('storage/'.$plato->imagen) }}" alt="{{ $plato->nombre }}" class="dish-image">
                                @else
                                    <img src="https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=600&q=80" alt="{{ $plato->nombre }}" class="dish-image">
                                @endif
                            </div>
                            <div class="dish-card-body">
                                <h3 class="dish-title">{{ $plato->nombre }}</h3>
                                <p class="dish-desc">{{ $plato->descripcion ?: 'Delicioso plato preparado con ingredientes frescos.' }}</p>
                                
                                <div class="dish-footer">
                                    <span class="dish-price">${{ number_format($plato->precio, 2, '.', ',') }}</span>
                                    <span class="category-pill-badge">{{ $categoriaNombre }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- PAGINACIÓN --}}
                @if($platos->hasPages())
                    <div style="margin-top: 32px; display: flex; justify-content: center;">
                        {{ $platos->links() }}
                    </div>
                @endif
            @else
                <div class="section-card">
                    <div class="empty-icon-wrapper green">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                        </svg>
                    </div>
                    <p class="empty-text">No se encontraron platos disponibles en este momento.</p>
                </div>
            @endif

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