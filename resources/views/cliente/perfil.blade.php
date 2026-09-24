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
            <a href="{{ route('cliente.pedidos') }}" class="nav-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
                <span>Mis Pedidos</span>
            </a>
            <a href="{{ route('cliente.perfil') }}" class="nav-link is-active">
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
                <h1 class="topbar-title">Mi Perfil</h1>
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

            <div class="profile-card" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2ece6; padding: 28px 32px 36px 32px; max-width: 520px; box-shadow: 0 1px 4px rgba(0,0,0,0.02);">
                {{-- CARD HEADER WITH BOTTOM BORDER --}}
                <div style="display: flex; align-items: center; gap: 8px; font-size: 1.05rem; font-weight: 800; color: #064e3b; padding-bottom: 16px; border-bottom: 1px solid #e2ece6; margin-bottom: 24px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    <span>Mi Perfil</span>
                </div>

                {{-- AVATAR CIRCLE --}}
                <div style="width: 64px; height: 64px; border-radius: 50%; background: #16a34a; color: white; font-size: 1.8rem; font-weight: 800; display: flex; align-items: center; justify-content: center; margin-bottom: 24px; box-shadow: 0 3px 10px rgba(22, 163, 74, 0.2);">
                    {{ strtoupper(substr($usuario->nombre ?? 'M', 0, 1)) }}
                </div>

                {{-- FIELDS LIST --}}
                <div style="display: flex; flex-direction: column; gap: 14px;">
                    
                    {{-- Nombre --}}
                    <div style="display: flex; align-items: center; gap: 14px; padding-bottom: 14px; border-bottom: 1px solid #f1f7f3;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: #e6f4ea; display: flex; align-items: center; justify-content: center; color: #16a34a; flex-shrink: 0;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.76rem; color: #64748b; font-weight: 600; line-height: 1.2;">Nombre completo</span>
                            <strong style="display: block; font-size: 0.92rem; color: #1e293b; font-weight: 700; margin-top: 2px;">{{ $usuario->nombre }}</strong>
                        </div>
                    </div>

                    {{-- Rol --}}
                    <div style="display: flex; align-items: center; gap: 14px; padding-bottom: 14px; border-bottom: 1px solid #f1f7f3;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: #e6f4ea; display: flex; align-items: center; justify-content: center; color: #16a34a; flex-shrink: 0;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.76rem; color: #64748b; font-weight: 600; line-height: 1.2;">Rol</span>
                            <strong style="display: block; font-size: 0.92rem; color: #1e293b; font-weight: 700; margin-top: 2px;">Cliente</strong>
                        </div>
                    </div>

                    {{-- Estado --}}
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: #e6f4ea; display: flex; align-items: center; justify-content: center; color: #16a34a; flex-shrink: 0;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.76rem; color: #64748b; font-weight: 600; line-height: 1.2;">Estado de cuenta</span>
                            <strong style="display: block; font-size: 0.92rem; color: #16a34a; font-weight: 700; margin-top: 2px;">Activo</strong>
                        </div>
                    </div>

                </div>
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
