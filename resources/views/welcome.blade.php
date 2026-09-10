<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="El Cielo — Plataforma digital del Centro Vacacional y Recreacional.">
  <title>El Cielo — Sistema de Gestión Integral</title>

  {{-- Fuentes Google: Outfit + Playfair Display --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600;1,700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/welcome/welcome.css') }}">
</head>

<body>

{{-- ══════════════════════════════════════════════════════════
     NAVBAR
════════════════════════════════════════════════════════════ --}}
<nav class="navbar" id="navbar">
  <a href="{{ url('/') }}" class="nav-brand">
    <img src="{{ asset('elcielo/assets/img/hero.png') }}?v={{ time() }}" alt="El Cielo">
    <span>El Cielo</span>
  </a>

  <ul class="nav-links">
    <li><a href="#caracteristicas">Características</a></li>
    <li><a href="#roles">Roles</a></li>
    <li><a href="#contacto">Contacto</a></li>
  </ul>

  <div class="nav-actions">
    @guest
      <a href="{{ route('login') }}" class="btn-nav-login">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
          <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
          <polyline points="10 17 15 12 10 7"/>
          <line x1="15" y1="12" x2="3" y2="12"/>
        </svg>
        <span>Ingresar al sistema</span>
      </a>
      <a href="{{ route('register') }}" class="btn-nav-register">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
          <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
          <circle cx="9" cy="7" r="4"/>
          <line x1="19" y1="8" x2="19" y2="14"/>
          <line x1="22" y1="11" x2="16" y2="11"/>
        </svg>
        <span>Crear cuenta</span>
      </a>
    @endguest

    @auth
      @php $rol = auth()->user()->role?->nombre; @endphp
      @if($rol === 'Administrador')
        <a href="{{ route('admin.dashboard') }}" class="btn-nav-account">Mi panel</a>
      @elseif($rol === 'Mesero')
        <a href="{{ route('mesero.dashboard') }}" class="btn-nav-account">Mi panel</a>
      @elseif($rol === 'Cocinero')
        <a href="{{ route('cocinero.dashboard') }}" class="btn-nav-account">Mi panel</a>
      @elseif($rol === 'Cliente')
        <a href="{{ route('cliente.dashboard') }}" class="btn-nav-account">Mi panel</a>
      @endif
      <form method="POST" action="{{ route('logout') }}" style="display:inline;">
        @csrf
        <button type="submit" class="btn-nav-logout">Cerrar sesión</button>
      </form>
    @endauth
  </div>
</nav>


{{-- ══════════════════════════════════════════════════════════
     HERO
════════════════════════════════════════════════════════════ --}}
<section class="hero" id="inicio">

  <div class="hero-bg"></div>
  <div class="hero-overlay"></div>

  <div class="hero-content">

    <div class="hero-badge">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
        <path d="M17 8C8 10 5.9 16.17 3.82 21.34L5.71 22l1-2.3A4.49 4.49 0 0 0 8 20C19 20 22 3 22 3c-1 2-8 2.25-13 3.25S2 11.5 2 13.5s1.75 3.75 1.75 3.75C7 8 17 8 17 8z"/>
      </svg>
      <span>SISTEMA DE GESTIÓN INTEGRAL</span>
    </div>

    <h1 class="hero-title">
      Bienvenido a<br>
      <span class="brand-highlight">El Cielo</span>
    </h1>

    <p class="hero-subtitle">
      La plataforma digital del Centro Vacacional y Recreacional El Cielo.<br>
      Gestiona pedidos, inventario, reportes y más desde un solo lugar.
    </p>

    @auth
      <div class="hero-actions">
        @php $rol = auth()->user()->role?->nombre; @endphp
        @if($rol === 'Administrador')
          <a href="{{ route('admin.dashboard') }}" class="btn-primary">Ir al Dashboard</a>
        @elseif($rol === 'Mesero')
          <a href="{{ route('mesero.dashboard') }}" class="btn-primary">Ir al Dashboard</a>
        @elseif($rol === 'Cocinero')
          <a href="{{ route('cocinero.dashboard') }}" class="btn-primary">Ir al Dashboard</a>
        @elseif($rol === 'Cliente')
          <a href="{{ route('cliente.dashboard') }}" class="btn-primary">Ir a mi cuenta</a>
        @endif
      </div>
    @endauth

  </div>

  <a href="#stats" class="hero-scroll">
    <span>DESCUBRIR</span>
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="6 9 12 15 18 9"/>
    </svg>
  </a>

</section>


{{-- ══════════════════════════════════════════════════════════
     STATS BAR
════════════════════════════════════════════════════════════ --}}
<div class="stats-bar" id="stats">
  <div class="stat-item">
    <span class="num">4</span>
    <span class="lbl">Roles de usuario</span>
  </div>
  <div class="stat-item">
    <span class="num">100%</span>
    <span class="lbl">Web — Sin instalación</span>
  </div>
  <div class="stat-item">
    <span class="num">24/7</span>
    <span class="lbl">Disponibilidad</span>
  </div>
  <div class="stat-item">
    <span class="num">&infin;</span>
    <span class="lbl">Pedidos gestionados</span>
  </div>
</div>


{{-- ══════════════════════════════════════════════════════════
     CARACTERÍSTICAS
════════════════════════════════════════════════════════════ --}}
<section class="section" id="caracteristicas">
  <p class="section-label">&#10024; ¿Qué ofrece?</p>
  <h2 class="section-title">Todo lo que necesitas en un solo sistema</h2>
  <p class="section-sub">
    Desde la toma del pedido hasta el reporte de ventas,
    El Cielo cubre cada etapa del servicio.
  </p>

  <div class="features-grid">

    <div class="feature-card">
      <div class="feature-icon fi-blue">
        <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 12h6M9 16h4"/></svg>
      </div>
      <h3>Gestión de Pedidos</h3>
      <p>El mesero toma pedidos desde el menú digital, los envía a cocina y gestiona el cobro con método de pago.</p>
    </div>

    <div class="feature-card">
      <div class="feature-icon fi-orange">
        <svg viewBox="0 0 24 24"><path d="M3 3h18"/><path d="M3 9c0 4.97 4.03 9 9 9s9-4.03 9-9"/><path d="M12 18v3"/><path d="M8 21h8"/></svg>
      </div>
      <h3>Panel de Cocina</h3>
      <p>El cocinero ve los pedidos en tiempo real, actualiza el estado y recibe alertas de pedidos urgentes.</p>
    </div>

    <div class="feature-card">
      <div class="feature-icon fi-green">
        <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
      </div>
      <h3>Reportes de Ventas</h3>
      <p>Filtra por periodo, platillo, categoría y método de pago. Exporta informes en PDF con un clic.</p>
    </div>

    <div class="feature-card">
      <div class="feature-icon fi-purple">
        <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
      </div>
      <h3>Control de Inventario</h3>
      <p>Gestiona materia prima con alertas de stock mínimo y descuento automático al procesar ventas.</p>
    </div>

    <div class="feature-card">
      <div class="feature-icon fi-pink">
        <svg viewBox="0 0 24 24"><path d="M4 6h16M4 10h16M4 14h8M4 18h6"/></svg>
      </div>
      <h3>Menú Digital</h3>
      <p>El cliente explora el menú con fotos, precios y categorías. Ve el estado de su pedido en tiempo real.</p>
    </div>

    <div class="feature-card">
      <div class="feature-icon fi-teal">
        <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
      </div>
      <h3>Asistente Virtual</h3>
      <p>Chatbot integrado que responde dudas frecuentes y permite enviar quejas directamente al administrador.</p>
    </div>

  </div>
</section>


{{-- ══════════════════════════════════════════════════════════
     ROLES
════════════════════════════════════════════════════════════ --}}
<section class="section roles-section" id="roles">
  <p class="section-label">Acceso por Rol</p>
  <h2 class="section-title">Un panel para cada persona</h2>
  <p class="section-sub">Cada usuario accede únicamente a las funciones de su rol. Seguro y organizado.</p>

  <div class="roles-grid">

    <div class="role-card">
      <div class="role-avatar ra-cyan">
        <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
      </div>
      <h3>Administrador</h3>
      <p>Gestiona usuarios, menú, inventario y accede a todos los reportes de ventas.</p>
    </div>

    <div class="role-card">
      <div class="role-avatar ra-green">
        <svg viewBox="0 0 24 24"><path d="M3 3h18"/><path d="M3 9c0 4.97 4.03 9 9 9s9-4.03 9-9"/><path d="M12 18v3"/><path d="M8 21h8"/></svg>
      </div>
      <h3>Mesero</h3>
      <p>Toma pedidos, gestiona la cuenta del cliente y registra el pago con método seleccionado.</p>
    </div>

    <div class="role-card">
      <div class="role-avatar ra-orange">
        <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 12l2 2 4-4"/></svg>
      </div>
      <h3>Cocinero</h3>
      <p>Ve los pedidos pendientes en tiempo real y actualiza el estado de preparación.</p>
    </div>

    <div class="role-card">
      <div class="role-avatar ra-purple">
        <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      </div>
      <h3>Cliente</h3>
      <p>Explora el menú, consulta sus pedidos y usa el asistente virtual para resolver dudas.</p>
    </div>

  </div>
</section>


{{-- ══════════════════════════════════════════════════════════
     FOOTER
════════════════════════════════════════════════════════════ --}}
<footer class="footer" id="contacto">

  <a href="{{ url('/') }}" class="footer-brand">
    <img src="{{ asset('elcielo/assets/img/hero.png') }}?v={{ time() }}" alt="El Cielo">
    <span>El Cielo</span>
  </a>

  <span class="footer-copy">
    &copy; {{ date('Y') }} Centro Vacacional El Cielo &middot; Cauca, Colombia
  </span>

  <div class="footer-links">
    <a href="https://instagram.com/centro_vacacional_elcielo" target="_blank" rel="noopener noreferrer">
      <svg viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="5"/><circle cx="17.5" cy="6.5" r="1.2" fill="currentColor" stroke="none"/></svg>
      centro_vacacional_elcielo
    </a>
    <a href="mailto:centrovacacionaelcielo@gmail.com">
      <svg viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="2,4 12,13 22,4"/></svg>
      centrovacacionaelcielo@gmail.com
    </a>
    <a href="tel:3182852854">
      <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 12 19.79 19.79 0 01.22 3.38 2 2 0 012.18 1h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006.91 6.91l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
      318 2852854
    </a>
  </div>

</footer>

{{-- Scroll navbar effect --}}
<script>
  const nav = document.getElementById('navbar');
  window.addEventListener('scroll', () => {
    nav.classList.toggle('scrolled', window.scrollY > 40);
  });
</script>

</body>
</html>

