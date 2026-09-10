<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel de Administrador - El Cielo')</title>
    
    <!-- Google Fonts & Chart.js -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin/admin-layout.css') }}?v={{ time() }}">
    @stack('styles')
</head>
<body class="admin-body">

<!-- Session Destroyer Script -->
<script src="{{ asset('js/session-destroyer.js') }}"></script>

    <!-- SIDEBAR -->
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <div class="logo-circle">
                <img src="{{ asset('elcielo/assets/img/hero.png') }}?v={{ time() }}" alt="El Cielo Logo">
            </div>
            <h2 class="brand-title">El Cielo</h2>
            <p class="brand-subtitle">CENTRO VACACIONAL Y RECREACIONAL</p>
        </div>

        <nav class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.supervision') ? 'active' : '' }}">
                <svg class="sidebar-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
                <span>Estadísticas</span>
            </a>

            <a href="{{ route('admin.reportes.ventas') }}" class="sidebar-link {{ request()->routeIs('admin.reportes.*') ? 'active' : '' }}">
                <svg class="sidebar-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                <span>Reportes</span>
            </a>

            <a href="{{ route('admin.pedidos.index') }}" class="sidebar-link {{ request()->routeIs('admin.pedidos.*') ? 'active' : '' }}">
                <svg class="sidebar-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="5" y="3" width="14" height="18" rx="2"></rect>
                    <line x1="9" y1="7" x2="15" y2="7"></line>
                    <line x1="9" y1="11" x2="15" y2="11"></line>
                    <line x1="9" y1="15" x2="13" y2="15"></line>
                </svg>
                <span>Pedidos</span>
            </a>

            <a href="{{ route('admin.usuarios.index') }}" class="sidebar-link {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">
                <svg class="sidebar-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                <span>Usuarios</span>
            </a>

            <a href="{{ route('admin.inventario.index') }}" class="sidebar-link {{ request()->routeIs('admin.inventario.*') ? 'active' : '' }}">
                <svg class="sidebar-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                </svg>
                <span>Inventario</span>
            </a>

        </nav>
    </aside>

    <!-- MAIN CONTAINER -->
    <div class="admin-wrapper">
        <!-- TOPBAR -->
        <header class="admin-topbar">
            <div class="topbar-title-wrapper">
                <span class="title-accent-bar"></span>
                <h1 class="topbar-title">@yield('page_title', 'Reportes de Ventas')</h1>
            </div>

            <div class="topbar-user-area">
                <div class="user-pill" id="userPillBtn">
                    <div class="user-avatar">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                    </div>
                    <span class="user-name">{{ auth()->user()->nombre ?? 'admin' }}</span>
                    <svg class="chevron-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M6 9l6 6 6-6"/>
                    </svg>

                    <div class="user-dropdown-menu" id="userDropdown">
                        <div class="dropdown-header">
                            <strong>{{ auth()->user()->nombre ?? 'admin' }}</strong>
                            <small>{{ auth()->user()->correo ?? 'admin@elcielo.com' }}</small>
                        </div>
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
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

        <!-- CONTENT AREA -->
        <main class="admin-main-content">
            @if(session('success'))
                <div class="alert alert-success">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pillBtn = document.getElementById('userPillBtn');
            const dropdown = document.getElementById('userDropdown');
            if (pillBtn && dropdown) {
                pillBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdown.classList.toggle('show');
                });
                document.addEventListener('click', function(e) {
                    if (!pillBtn.contains(e.target)) {
                        dropdown.classList.remove('show');
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
