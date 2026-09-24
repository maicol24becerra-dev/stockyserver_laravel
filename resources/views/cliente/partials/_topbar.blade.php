{{-- TOPBAR CLIENTE --}}
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
