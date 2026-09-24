{{-- SIDEBAR COCINERO --}}
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
