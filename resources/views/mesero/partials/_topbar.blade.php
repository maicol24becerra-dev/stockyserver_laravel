{{-- TOPBAR MESERO --}}
<header class="topbar">
    <h1>{{ $section === 'crear' ? 'Crear Pedido' : ($section === 'historial' ? 'Historial' : 'Pedidos Activos') }}</h1>
    <div class="profile-menu">
        <button class="profile" type="button" onclick="toggleProfileMenu()" aria-expanded="false" aria-controls="profile-dropdown">
            <b>{{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}</b>
            <span>{{ auth()->user()->nombre }}<small>Mesero</small></span>
            <i>⌵</i>
        </button>
        <div class="profile-dropdown" id="profile-dropdown">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </div>
</header>

{{-- ALERTAS --}}
@if(session('success')) <div class="notice success">{{ session('success') }}</div> @endif
@if($errors->any()) <div class="notice error">{{ $errors->first() }}</div> @endif
