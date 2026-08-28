@php
    $section = request('seccion', 'activos');
    $activeOrders = $pedidos->filter(fn ($pedido) => !in_array(strtolower(trim($pedido->estado)), ['entregado', 'cancelado']));
    $todayOrders = $pedidos->filter(fn ($pedido) => $pedido->fecha?->isToday());
    $pendingOrders = $activeOrders->filter(fn ($pedido) => strtolower(trim($pedido->estado)) === 'pendiente');
    $completedOrders = $todayOrders->filter(fn ($pedido) => strtolower(trim($pedido->estado)) === 'entregado');
    $categories = $platos->pluck('categoria')->filter()->unique()->values();
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ucfirst($section === 'crear' ? 'Crear pedido' : ($section === 'historial' ? 'Historial' : 'Pedidos activos')) }} - El Cielo</title>
    <link rel="stylesheet" href="{{ asset('css/mesero-dashboard.css') }}">
</head>
<body>
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-mark">EC</div>
            <strong>El Cielo</strong>
            <span>CENTRO VACACIONAL<br>Y RECREACIONAL</span>
        </div>
        <nav>
            <a class="nav-link {{ $section === 'activos' ? 'is-active' : '' }}" href="{{ route('mesero.dashboard', ['seccion' => 'activos']) }}"><span>▣</span> Pedidos Activos</a>
            <a class="nav-link {{ $section === 'crear' ? 'is-active' : '' }}" href="{{ route('mesero.dashboard', ['seccion' => 'crear']) }}"><span>✦</span> Crear Pedido</a>
            <a class="nav-link {{ $section === 'historial' ? 'is-active' : '' }}" href="{{ route('mesero.dashboard', ['seccion' => 'historial']) }}"><span>↶</span> Historial</a>
        </nav>
    </aside>

    <main class="workspace">
        <header class="topbar">
            <h1>{{ $section === 'crear' ? 'Crear Pedido' : ($section === 'historial' ? 'Historial' : 'Pedidos Activos') }}</h1>
            <div class="profile-menu">
                <button class="profile" type="button" onclick="toggleProfileMenu()" aria-expanded="false" aria-controls="profile-dropdown">
                    <b>{{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}</b><span>{{ auth()->user()->nombre }}<small>Mesero</small></span><i>⌄</i>
                </button>
                <div class="profile-dropdown" id="profile-dropdown">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"><span>↪</span> Cerrar sesión</button>
                    </form>
                </div>
            </div>
        </header>

        @if(session('success')) <div class="notice success">{{ session('success') }}</div> @endif
        @if($errors->any()) <div class="notice error">{{ $errors->first() }}</div> @endif

        @if($section === 'activos')
            <section class="content-section">
                <div class="stats">
                    <article><span class="stat-icon cyan">▤</span><div><small>TOTAL HOY</small><strong>{{ $todayOrders->count() }}</strong></div></article>
                    <article><span class="stat-icon orange">◷</span><div><small>PENDIENTES</small><strong>{{ $pendingOrders->count() }}</strong></div></article>
                    <article><span class="stat-icon green">✓</span><div><small>COMPLETADOS</small><strong>{{ $completedOrders->count() }}</strong></div></article>
                </div>
                <div class="panel">
                    <div class="panel-heading"><h2>Pedidos en curso</h2><a class="button green-button" href="{{ route('mesero.dashboard', ['seccion' => 'crear']) }}">＋ Nuevo Pedido</a></div>
                    <div class="tabs"><a class="tab active" href="#todos">Todos</a><a class="tab" href="#pendientes">Pendientes</a><a class="tab" href="#preparacion">En preparación</a><a class="tab" href="#entregados">Entregados</a></div>
                    @forelse($activeOrders as $pedido)
                        @php $total = $pedido->items->sum(fn ($item) => $item->cantidad * $item->precio_unitario); $estado = strtolower(trim($pedido->estado)); @endphp
                        <article class="order-row" id="{{ $estado }}"><div><strong>Pedido #{{ $pedido->id_pedido }}</strong><small>{{ $pedido->cliente?->usuario?->nombre ?? 'Sin cliente' }} · {{ $pedido->fecha?->format('d/m/Y H:i') }}</small></div><span class="status {{ str_replace(' ', '-', $estado) }}">{{ ucfirst($estado) }}</span><b>${{ number_format($total, 0, ',', '.') }}</b><a class="text-link" href="{{ route('admin.pedidos.show', $pedido) }}">Ver detalle</a></article>
                    @empty
                        <div class="empty"><span>▤</span><p>No hay pedidos activos.</p><a href="{{ route('mesero.dashboard', ['seccion' => 'crear']) }}">Crear un pedido</a></div>
                    @endforelse
                </div>
            </section>
        @elseif($section === 'crear')
            <section class="content-section create-layout">
                <div class="catalog"><div class="panel-heading"><h2>Crear Pedido</h2><button class="button green-button" type="button" onclick="document.getElementById('cart').classList.toggle('open')">＋ Ver pedido</button></div><input class="search" id="search" type="search" placeholder="⌕  Buscar plato..." oninput="filterDishes()"><div class="category"><span class="active">Todos</span>@foreach($categories as $category)<span>{{ $category }}</span>@endforeach</div><div class="dish-grid">
                    @forelse($platos as $plato)
                        <article class="dish" data-name="{{ strtolower($plato->nombre) }}"><div class="dish-image">@if($plato->imagen)<img src="{{ asset('storage/'.$plato->imagen) }}" alt="{{ $plato->nombre }}">@else<span>🍽</span>@endif</div><div class="dish-body"><h3>{{ $plato->nombre }}</h3><small>{{ $plato->categoria }}</small><strong>${{ number_format($plato->precio, 0, ',', '.') }}</strong><button type="button" onclick="addDish({{ $plato->id_plato }}, '{{ addslashes($plato->nombre) }}', {{ $plato->precio }})">＋ Añadir</button></div></article>
                    @empty <div class="empty"><p>No hay platos disponibles.</p></div> @endforelse
                </div></div>
                <aside class="cart open" id="cart"><div class="cart-heading"><h2>🛒 Pedido Actual</h2><button type="button" onclick="document.getElementById('cart').classList.remove('open')">×</button></div><form action="{{ route('admin.pedidos.store') }}" method="POST" id="order-form">@csrf<div class="client-box"><label for="client">CLIENTE</label><select name="id_cliente" id="client" required><option value="">Seleccionar cliente</option>@foreach($clientes as $cliente)<option value="{{ $cliente->id_cliente }}">{{ $cliente->usuario?->nombre ?? 'Cliente #'.$cliente->id_cliente }}</option>@endforeach</select></div><div id="cart-items" class="cart-items"><p>Aún no has añadido platos.</p></div><div id="item-inputs"></div><div class="cart-total"><span>Total:</span><strong id="total">$0</strong></div><button class="button green-button full" type="submit">➤ Crear Pedido</button></form></aside>
            </section>
        @else
            <section class="content-section"><div class="panel history"><div class="panel-heading"><h2>Historial de Pedidos</h2></div><div class="history-table"><div class="history-head"><span>#</span><span>FECHA</span><span>CLIENTE</span><span>ESTADO</span><span>TOTAL</span></div>@forelse($pedidos as $pedido)<div class="history-row"><b>#{{ $pedido->id_pedido }}</b><span>{{ $pedido->fecha?->format('d/m/Y H:i') }}</span><span>{{ $pedido->cliente?->usuario?->nombre ?? 'Sin cliente' }}</span><span class="status {{ str_replace(' ', '-', strtolower($pedido->estado)) }}">{{ ucfirst($pedido->estado) }}</span><b>${{ number_format($pedido->items->sum(fn ($item) => $item->cantidad * $item->precio_unitario), 0, ',', '.') }}</b></div>@empty<div class="empty"><p>No hay pedidos registrados.</p></div>@endforelse</div></div></section>
        @endif
    </main>
    <script>
        const cartItems = {};
        function addDish(id, name, price) { cartItems[id] = cartItems[id] || { id, name, price, quantity: 0 }; cartItems[id].quantity++; renderCart(); document.getElementById('cart').classList.add('open'); updateCartBadge(); }
        function changeDish(id, amount) { cartItems[id].quantity += amount; if (cartItems[id].quantity <= 0) delete cartItems[id]; renderCart(); updateCartBadge(); }
        function renderCart() { const items = Object.values(cartItems); document.getElementById('cart-items').innerHTML = items.length ? items.map(item => `<div class="cart-item"><span>${item.name}<small>$${item.price.toLocaleString()} · ${item.quantity} u.</small></span><button type="button" onclick="changeDish(${item.id}, -1)">−</button><b>${item.quantity}</b><button type="button" onclick="changeDish(${item.id}, 1)">＋</button></div>`).join('') : '<p>Aún no has añadido platos.</p>'; document.getElementById('total').textContent = '$' + items.reduce((total, item) => total + item.price * item.quantity, 0).toLocaleString(); document.getElementById('item-inputs').innerHTML = items.map((item, index) => `<input type="hidden" name="items[${index}][id_plato]" value="${item.id}"><input type="hidden" name="items[${index}][cantidad]" value="${item.quantity}">`).join(''); }
        function updateCartBadge() { const count = Object.values(cartItems).reduce((sum, i) => sum + i.quantity, 0); const badge = document.getElementById('cart-badge'); if (badge) { badge.textContent = count; badge.style.display = count > 0 ? 'flex' : 'none'; } }
        function toggleCart() { document.getElementById('cart').classList.toggle('open'); }
        function filterDishes() { const query = document.getElementById('search').value.toLowerCase(); document.querySelectorAll('.dish').forEach(dish => dish.hidden = !dish.dataset.name.includes(query)); }
        function toggleProfileMenu() { const menu = document.querySelector('.profile-menu'); const button = menu.querySelector('.profile'); menu.classList.toggle('is-open'); button.setAttribute('aria-expanded', menu.classList.contains('is-open')); }
        document.addEventListener('click', event => { if (!event.target.closest('.profile-menu')) document.querySelector('.profile-menu')?.classList.remove('is-open'); });
    </script>

    {{-- Botón flotante del carrito (solo visible en sección crear, mobile) --}}
    @if($section === 'crear')
    <button class="cart-fab" id="cart-fab" onclick="toggleCart()" aria-label="Ver pedido actual">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/>
        </svg>
        <span class="cart-fab-badge" id="cart-badge" style="display:none;">0</span>
    </button>
    @endif
</body>
</html>
