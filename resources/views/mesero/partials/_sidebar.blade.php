{{-- SIDEBAR MESERO --}}
<aside class="sidebar">
    <div class="brand">
        <img src="{{ asset('images/logo-circle.png') }}?v={{ time() }}" alt="El Cielo" class="brand-logo-img">
        <strong>El Cielo</strong>
        <span>CENTRO VACACIONAL<br>Y RECREACIONAL</span>
    </div>
    <nav>
        <a class="nav-link {{ $section === 'activos' ? 'is-active' : '' }}" href="{{ route('mesero.dashboard', ['seccion' => 'activos']) }}">
            <svg viewBox="0 0 24 24">
                <rect x="5" y="3" width="14" height="18" rx="2"/>
                <line x1="9" y1="7" x2="15" y2="7"/>
                <line x1="9" y1="11" x2="15" y2="11"/>
                <line x1="9" y1="15" x2="13" y2="15"/>
            </svg>
            <span>Pedidos Activos</span>
        </a>
        <a class="nav-link {{ $section === 'crear' ? 'is-active' : '' }}" href="{{ route('mesero.dashboard', ['seccion' => 'crear']) }}">
            <svg viewBox="0 0 24 24">
                <path d="M18 2v20M2 12h20M2 2l10 10"/>
            </svg>
            <span>Crear Pedido</span>
        </a>
        <a class="nav-link {{ $section === 'historial' ? 'is-active' : '' }}" href="{{ route('mesero.dashboard', ['seccion' => 'historial']) }}">
            <svg viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </svg>
            <span>Historial</span>
        </a>
    </nav>
</aside>
