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
    <link rel="icon" href="{{ asset('images/logo-circle.png') }}?v={{ time() }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mesero/mesero-dashboard.css') }}?v={{ time() }}">
</head>
<body>

<!-- Session Destroyer Script -->
<script src="{{ asset('js/session-destroyer.js') }}"></script>
    {{-- SIDEBAR --}}
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

    {{-- WORKSPACE --}}
    <main class="workspace">
        {{-- TOPBAR --}}
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

        {{-- SECCIÓN PEDIDOS ACTIVOS --}}
        @if($section === 'activos')
            <section class="content-section">
                {{-- STATS METRICS --}}
                <div class="stats">
                    <article>
                        <span class="stat-icon cyan">
                            <svg viewBox="0 0 24 24">
                                <rect x="5" y="3" width="14" height="18" rx="2"/>
                                <line x1="9" y1="8" x2="15" y2="8"/>
                                <line x1="9" y1="12" x2="15" y2="12"/>
                                <line x1="9" y1="16" x2="13" y2="16"/>
                            </svg>
                        </span>
                        <div>
                            <small>TOTAL HOY</small>
                            <strong>{{ $todayOrders->count() }}</strong>
                        </div>
                    </article>
                    <article>
                        <span class="stat-icon orange">
                            <svg viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </span>
                        <div>
                            <small>PENDIENTES</small>
                            <strong>{{ $pendingOrders->count() }}</strong>
                        </div>
                    </article>
                    <article>
                        <span class="stat-icon green">
                            <svg viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="9 12 11 14 15 10"/>
                            </svg>
                        </span>
                        <div>
                            <small>COMPLETADOS</small>
                            <strong>{{ $completedOrders->count() }}</strong>
                        </div>
                    </article>
                </div>

                {{-- PANEL PRINCIPAL --}}
                <div class="panel">
                    <div class="panel-heading">
                        <h2>Pedidos en curso</h2>
                        <a class="button green-button" href="{{ route('mesero.dashboard', ['seccion' => 'crear']) }}">
                            + Nuevo Pedido
                        </a>
                    </div>

                    {{-- PÍLDORAS FILTRO --}}
                    <div class="tabs">
                        <a class="tab active" href="#todos" onclick="filterStatus('todos', this); return false;">Todos</a>
                        <a class="tab" href="#pendiente" onclick="filterStatus('pendiente', this); return false;">Pendientes</a>
                        <a class="tab" href="#en-preparacion" onclick="filterStatus('en-preparacion', this); return false;">En preparación</a>
                        <a class="tab" href="#entregado" onclick="filterStatus('entregado', this); return false;">Entregados</a>
                    </div>

                    {{-- LISTADO O EMPTY STATE --}}
                    <div id="orders-container">
                        @forelse($activeOrders as $pedido)
                            @php
                                $total = $pedido->items->sum(fn ($item) => $item->cantidad * $item->precio_unitario);
                                $estado = strtolower(trim($pedido->estado));
                                $estadoClass = str_replace(' ', '-', $estado);
                            @endphp
                            <article class="order-row" data-status="{{ $estadoClass }}">
                                <div>
                                    <strong>
                                        Pedido #{{ $pedido->id_pedido }}
                                        @php
                                            $prioridad = $pedido->prioridad ?? 'normal';
                                            $iconosPrioridad = [
                                                'baja' => ['⬇️', '#6c757d'],
                                                'normal' => ['➡️', '#0d6efd'], 
                                                'alta' => ['⬆️', '#fd7e14'],
                                                'urgente' => ['🚨', '#dc3545']
                                            ];
                                        @endphp
                                        <span style="
                                            color: {{ $iconosPrioridad[$prioridad][1] }};
                                            font-size: 0.8em;
                                            margin-left: 5px;
                                        " title="Prioridad: {{ ucfirst($prioridad) }}">
                                            {{ $iconosPrioridad[$prioridad][0] }}
                                        </span>
                                    </strong>
                                    <small>{{ $pedido->cliente?->usuario?->nombre ?? 'Sin cliente' }} · {{ $pedido->fecha?->format('d/m/Y H:i') }}</small>
                                </div>
                                <div>
                                    <span class="status {{ $estadoClass }}">{{ ucfirst($estado) }}</span>
                                </div>
                                <b>${{ number_format($total, 0, ',', '.') }}</b>
                                <div class="order-actions">
                                    @if($estado === 'pendiente')
                                        <button type="button" class="btn-action-order green" onclick="openAccountModal({{ $pedido->id_pedido }}, '{{ $pedido->cliente?->usuario?->nombre ?? 'mesero' }}', '{{ $estado }}', {{ $total }}, {{ json_encode($pedido->items->map(fn($item) => ['nombre' => $item->plato?->nombre, 'cantidad' => $item->cantidad, 'precio' => $item->precio_unitario])->values()) }})">
                                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3h18v18H3zM9 9h6M9 15h4M21 12H3"/></svg>
                                            Cuenta
                                        </button>
                                        <button type="button" class="btn-action-order blue" onclick="sendToKitchen({{ $pedido->id_pedido }})">
                                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M2 12h20"/></svg>
                                            En preparación
                                        </button>
                                        <button type="button" class="btn-action-order red" onclick="confirmCancelOrder({{ $pedido->id_pedido }})">
                                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                            Cancelar
                                        </button>
                                    @elseif($estado === 'listo')
                                        <button type="button" class="btn-action-order green" onclick="deliverOrder({{ $pedido->id_pedido }})">
                                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                            Entregar
                                        </button>
                                    @elseif($estado === 'entregado')
                                        @if(!$pedido->pago)
                                            <button type="button" class="btn-action-order green" onclick="openPaymentModal({{ $pedido->id_pedido }}, {{ $total }})">
                                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                                                Registrar Pago
                                            </button>
                                            <a href="{{ route('admin.pedidos.dividir-cuenta', $pedido) }}" class="btn-action-order orange">
                                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                                                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/>
                                                    <line x1="12" y1="17" x2="12" y2="21"/><line x1="2" y1="9" x2="22" y2="9"/>
                                                </svg>
                                                Dividir Cuenta
                                            </a>
                                        @endif
                                        <button type="button" class="btn-action-order gray" onclick="openAccountModal({{ $pedido->id_pedido }}, '{{ $pedido->cliente?->usuario?->nombre ?? 'mesero' }}', '{{ $estado }}', {{ $total }}, {{ json_encode($pedido->items->map(fn($item) => ['nombre' => $item->plato?->nombre, 'cantidad' => $item->cantidad, 'precio' => $item->precio_unitario])->values()) }})">
                                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3h18v18H3zM9 9h6M9 15h4M21 12H3"/></svg>
                                            Cuenta
                                        </button>
                                        <button type="button" class="btn-action-order red" onclick="confirmCancelOrder({{ $pedido->id_pedido }})">
                                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                            Cancelar
                                        </button>
                                    @endif
                                </div>
                            </article>
                        @empty
                            <div class="empty">
                                <svg class="empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="5" y="3" width="14" height="18" rx="2"/>
                                    <path d="M9 7h6M9 11h6M9 15h4"/>
                                </svg>
                                <p>No hay pedidos activos.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- MODAL CUENTA DEL PEDIDO --}}
                <div class="account-modal-overlay" id="account-modal">
                    <div class="account-modal-card">
                        <div class="account-modal-header">
                            <div>
                                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 9h6M9 15h4M21 12H3"/></svg>
                                <h3>Cuenta del Pedido</h3>
                            </div>
                            <button type="button" onclick="closeAccountModal()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                        </div>
                        <div class="account-modal-body">
                            <div class="account-info-row">
                                <div><small>PEDIDO</small><strong id="account-pedido">#0</strong></div>
                                <div><small>CLIENTE</small><strong id="account-cliente">-</strong></div>
                                <div><small>ESTADO</small><strong id="account-estado" class="status pendiente">Pendiente</strong></div>
                            </div>
                            <div class="account-items" id="account-items"></div>
                            <div class="account-total">
                                <span>Total</span>
                                <strong id="account-total-val">$0.00</strong>
                            </div>
                            <div id="account-add-section" class="account-add-section">
                                <p class="account-add-title">AÑADIR PLATO AL PEDIDO</p>
                                <div class="account-add-form">
                                    <select id="account-plato-select" class="account-select">
                                        <option value="">Selecciona un plato...</option>
                                        @foreach($platos as $plato)
                                            <option value="{{ $plato->id_plato }}" data-price="{{ $plato->precio }}" data-name="{{ $plato->nombre }}">{{ $plato->nombre }} - ${{ number_format($plato->precio, 2, '.', ',') }}</option>
                                        @endforeach
                                    </select>
                                    <input type="number" id="account-quantity-input" class="account-input" min="1" value="1">
                                    <button type="button" class="account-add-btn" onclick="addDishToOrder()">
                                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                        Añadir
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- MODAL REGISTRAR PAGO --}}
                <div class="payment-modal-overlay" id="payment-modal">
                    <div class="payment-modal-card">
                        <div class="payment-modal-header">
                            <div>
                                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                                <h3>Registrar Pago</h3>
                            </div>
                            <button type="button" onclick="closePaymentModal()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                        </div>
                        <form id="payment-form" method="POST">
                            @csrf
                            <div class="payment-modal-body">
                                <p id="payment-desc" class="payment-desc">Pedido #<span id="payment-pedido-id">0</span> — Seleccione el método de pago para registrar el cobro.</p>
                                
                                <div class="payment-method-section">
                                    <label class="payment-label">Método de pago</label>
                                    <div class="payment-select-wrapper">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                                        <select name="metodo_pago" id="payment-method-select" class="payment-select" required>
                                            <option value="efectivo" selected>💵 Efectivo</option>
                                            <option value="tarjeta">💳 Tarjeta</option>
                                            <option value="transferencia">🏦 Transferencia</option>
                                            <option value="nequi">📱 Nequi</option>
                                            <option value="daviplata">📲 Daviplata</option>
                                        </select>
                                    </div>
                                </div>

                                <input type="hidden" name="monto_pagado" id="payment-amount">
                                <input type="hidden" name="fecha_pago" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="payment-modal-footer">
                                <button type="submit" class="btn-payment-confirm">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    <span>Confirmar Pago</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- MODAL CONFIRMAR CANCELACIÓN --}}
                <div class="cancel-modal-overlay" id="cancel-modal">
                    <div class="cancel-modal-card">
                        <div class="cancel-modal-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="15" y1="9" x2="9" y2="15"/>
                                <line x1="9" y1="9" x2="15" y2="15"/>
                            </svg>
                        </div>
                        <h3>127.0.0.1:8080 dice</h3>
                        <p>¿Cancelar este pedido?</p>
                        <div class="cancel-modal-actions">
                            <button type="button" class="btn-cancel-yes" onclick="executeCancelOrder()">Aceptar</button>
                            <button type="button" class="btn-cancel-no" onclick="closeCancelModal()">Cancelar</button>
                        </div>
                    </div>
                </div>

                {{-- MODAL ESTADO ACTUALIZADO --}}
                <div class="status-updated-modal-overlay" id="status-updated-modal">
                    <div class="status-updated-modal-card">
                        <div class="status-updated-modal-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="9 12 11 14 15 10"/>
                            </svg>
                        </div>
                        <h3>Estado Actualizado</h3>
                        <p id="status-updated-text">Pedido #X marcado como Estado.</p>
                        <button type="button" class="btn-status-ok" onclick="closeStatusUpdatedModal()">OK</button>
                    </div>
                </div>
            </section>

        {{-- SECCIÓN CREAR PEDIDO --}}
        @elseif($section === 'crear')
            <section class="content-section create-layout">
                <div class="catalog">
                    <div class="panel-heading">
                        <h2>Crear Pedido</h2>
                        <a class="button green-button" href="{{ route('mesero.dashboard', ['seccion' => 'crear']) }}">
                            + Nuevo Pedido
                        </a>
                    </div>

                    <div class="search-wrap">
                        <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input class="search" id="search" type="search" placeholder="Buscar plato..." oninput="filterDishes()">
                    </div>

                    <div class="category">
                        <span class="active" onclick="filterCategory('todos', this)">Todos</span>
                        @foreach($categories as $category)
                            <span onclick="filterCategory('{{ strtolower($category) }}', this)">{{ $category }}</span>
                        @endforeach
                    </div>

                    <div class="dish-grid">
                        @forelse($platos as $plato)
                            <article class="dish" data-name="{{ strtolower($plato->nombre) }}" data-category="{{ strtolower($plato->categoria) }}">
                                <div class="dish-image">
                                    @if($plato->imagen)
                                        <img src="{{ asset('storage/'.$plato->imagen) }}" alt="{{ $plato->nombre }}">
                                    @else
                                        <span>🍽</span>
                                    @endif
                                </div>
                                <div class="dish-body">
                                    <h3>{{ $plato->nombre }}</h3>
                                    @if($plato->categoria)
                                        <span class="dish-category-badge">{{ $plato->categoria }}</span>
                                    @endif
                                    <strong>${{ number_format($plato->precio, 2, '.', ',') }}</strong>
                                    <button type="button" class="btn-add-dish" onclick="addDish({{ $plato->id_plato }}, '{{ addslashes($plato->nombre) }}', {{ $plato->precio }}, '{{ $plato->imagen ? asset('storage/'.$plato->imagen) : '' }}')">
                                        + Añadir
                                    </button>
                                </div>
                            </article>
                        @empty
                            <div class="empty"><p>No hay platos disponibles.</p></div>
                        @endforelse
                    </div>
                </div>

            </section>

            {{-- POPUP CARRITO FLOTANTE --}}
            <div class="cart-popup" id="cart-popup">
                <div class="cart-popup-header">
                    <div class="cart-popup-title">
                        <svg viewBox="0 0 24 24">
                            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                            <line x1="3" y1="6" x2="21" y2="6"/>
                            <path d="M16 10a4 4 0 01-8 0"/>
                        </svg>
                        <span>Pedido Actual</span>
                    </div>
                    <div class="cart-popup-actions">
                        <button type="button" class="cart-action-btn" onclick="clearCart()" title="Limpiar pedido">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            </svg>
                        </button>
                        <button type="button" class="cart-action-btn" onclick="toggleCart()" title="Cerrar">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"/>
                                <line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="cart-popup-body">
                    <form action="{{ route('admin.pedidos.store') }}" method="POST" id="order-form">
                        @csrf
                        <input type="hidden" name="id_cliente" id="selected-client-id" value="">

                        {{-- Caja Cliente --}}
                        <div class="cart-client-box">
                            <div class="cart-client-info">
                                <small>CLIENTE</small>
                                <strong id="display-client-name">Sin asignar</strong>
                            </div>
                            <button type="button" class="cart-client-change-btn" onclick="openClientModal()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                                <span>Cambiar</span>
                            </button>
                        </div>

                        {{-- Items del Carrito --}}
                        <div id="cart-items" class="cart-popup-items">
                            <div class="cart-popup-empty">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 8h1a4 4 0 0 1 0 8h-1"/>
                                    <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/>
                                    <line x1="6" y1="1" x2="6" y2="4"/>
                                    <line x1="10" y1="1" x2="10" y2="4"/>
                                    <line x1="14" y1="1" x2="14" y2="4"/>
                                </svg>
                                <p>Aún no has añadido platos.</p>
                            </div>
                        </div>

                        <div id="item-inputs"></div>

                        {{-- Total --}}
                        <div class="cart-popup-total">
                            <span>Total:</span>
                            <strong id="cart-total-val">$0.00</strong>
                        </div>

                        <button class="cart-submit-btn" type="button" onclick="confirmCreateOrder()">
                            <svg viewBox="0 0 24 24">
                                <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                            </svg>
                            <span>Crear Pedido</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- MODAL CONFIRMAR CREACIÓN PEDIDO --}}
            <div class="confirm-modal-overlay" id="confirm-create-modal">
                <div class="confirm-modal-card">
                    <div class="confirm-modal-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                    </div>
                    <h3>Crear pedido?</h3>
                    <p id="confirm-create-text">Se creara un pedido con X plato(s).</p>
                    <div class="confirm-modal-actions">
                        <button type="button" class="btn-confirm-yes" onclick="submitCreateOrder()">Sí, crear</button>
                        <button type="button" class="btn-confirm-no" onclick="closeConfirmCreate()">Cancelar</button>
                    </div>
                </div>
            </div>

            {{-- MODAL ASIGNAR CLIENTE --}}
            <div class="client-modal-overlay" id="client-modal">
                <div class="client-modal-card">
                    <div class="client-modal-header">
                        <h3>Asignar Cliente</h3>
                        <button type="button" class="client-modal-close" onclick="closeClientModal()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"/>
                                <line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button>
                    </div>
                    <p class="client-modal-desc">Busca el cliente por nombre. Puedes dejarlo sin asignar.</p>

                    <div class="client-modal-search">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input type="text" id="client-search-input" placeholder="Escribe el nombre del cliente..." oninput="filterClients()">
                    </div>

                    <div class="client-list" id="client-list">
                        @forelse($clientes as $cliente)
                            @php
                                $cNombre = $cliente->usuario?->nombre ?? 'Cliente #'.$cliente->id_cliente;
                                $cCorreo = $cliente->usuario?->correo ?? '';
                                $initial = strtoupper(substr($cNombre, 0, 1));
                            @endphp
                            <div class="client-option" data-id="{{ $cliente->id_cliente }}" data-name="{{ strtolower($cNombre) }}" onclick="selectClient('{{ $cliente->id_cliente }}', '{{ addslashes($cNombre) }}', this)">
                                <div class="client-option-left">
                                    <div class="client-option-avatar">{{ $initial }}</div>
                                    <div class="client-option-info">
                                        <strong>{{ $cNombre }}</strong>
                                        @if($cCorreo)<small>{{ $cCorreo }}</small>@endif
                                    </div>
                                </div>
                                <div class="client-option-check">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                </div>
                            </div>
                        @empty
                            <p class="cart-empty-text">No hay clientes registrados.</p>
                        @endforelse
                    </div>

                    <div class="client-modal-footer">
                        <button type="button" class="client-confirm-btn" onclick="confirmClient()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <span>Confirmar</span>
                        </button>
                        <button type="button" class="client-none-btn" onclick="clearClient()">Sin cliente</button>
                    </div>
                </div>
            </div>

        {{-- SECCIÓN HISTORIAL --}}
        @else
            <section class="content-section">
                <div class="panel">
                    <div class="panel-heading">
                        <h2>Historial de Pedidos</h2>
                    </div>
                    <div class="history-table">
                        <div class="history-head">
                            <span>#</span>
                            <span>FECHA</span>
                            <span>CLIENTE</span>
                            <span>ESTADO</span>
                        </div>
                        @forelse($pedidos as $pedido)
                            @php
                                $estado = strtolower(trim($pedido->estado));
                                $estadoClass = str_replace(' ', '-', $estado);
                            @endphp
                            <div class="history-row">
                                <b>#{{ $pedido->id_pedido }}</b>
                                <span>{{ $pedido->fecha?->format('d/m/Y H:i') }}</span>
                                <span>{{ $pedido->cliente?->usuario?->nombre ?? $pedido->usuario?->nombre ?? 'mesero' }}</span>
                                <div>
                                    <span class="status {{ $estadoClass }}">{{ ucfirst($estado) }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="empty"><p>No hay pedidos registrados.</p></div>
                        @endforelse
                    </div>
                </div>
            </section>
        @endif
    </main>

    {{-- BOTÓN FLOTANTE (FAB) --}}
    @if($section === 'crear')
    <button type="button" class="fab-basket" id="fab-basket" onclick="toggleCart()" aria-label="Ver pedido actual">
        <svg viewBox="0 0 24 24">
            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
            <line x1="3" y1="6" x2="21" y2="6"/>
            <path d="M16 10a4 4 0 01-8 0"/>
        </svg>
        <span class="fab-badge" id="fab-badge">0</span>
    </button>
    @else
    <a href="{{ route('mesero.dashboard', ['seccion' => 'crear']) }}" class="fab-basket" aria-label="Crear nuevo pedido">
        <svg viewBox="0 0 24 24">
            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
            <line x1="3" y1="6" x2="21" y2="6"/>
            <path d="M16 10a4 4 0 01-8 0"/>
        </svg>
    </a>
    @endif

    {{-- SCRIPTS --}}
    <script>
        const cartItems = {};
        let tempClientId = '';
        let tempClientName = 'Sin asignar';

        function toggleCart() {
            const popup = document.getElementById('cart-popup');
            if (popup) popup.classList.toggle('is-open');
        }

        function clearCart() {
            for (const key in cartItems) delete cartItems[key];
            renderCart();
            updateCartBadge();
        }

        function addDish(id, name, price, imgSrc) {
            cartItems[id] = cartItems[id] || { id, name, price, imgSrc: imgSrc || '', quantity: 0, notas: '' };
            cartItems[id].quantity++;
            renderCart();
            updateCartBadge();
            const popup = document.getElementById('cart-popup');
            if (popup && !popup.classList.contains('is-open')) popup.classList.add('is-open');
        }

        function changeDish(id, amount) {
            cartItems[id].quantity += amount;
            if (cartItems[id].quantity <= 0) delete cartItems[id];
            renderCart();
            updateCartBadge();
        }

        function updateNotes(id, notes) {
            if (cartItems[id]) {
                cartItems[id].notas = notes;
            }
        }

        function renderCart() {
            const items = Object.values(cartItems);
            const container = document.getElementById('cart-items');
            if (!container) return;

            if (!items.length) {
                container.innerHTML = `
                    <div class="cart-popup-empty">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8h1a4 4 0 0 1 0 8h-1"/>
                            <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/>
                            <line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/>
                        </svg>
                        <p>Aún no has añadido platos.</p>
                    </div>
                `;
            } else {
                container.innerHTML = items.map(item => `
                    <div class="cart-popup-item">
                        <div class="cart-popup-item-info">
                            ${item.imgSrc ? `<img src="${item.imgSrc}" alt="${item.name}" class="cart-popup-item-thumb">` : '<div class="cart-popup-item-thumb cart-popup-item-no-img">🍽</div>'}
                            <div class="cart-popup-item-details">
                                <strong>${item.name}</strong>
                                <span>$${(item.price * item.quantity).toLocaleString('es-CO')}</span>
                            </div>
                        </div>
                        <div class="cart-popup-item-qty">
                            <button type="button" onclick="changeDish(${item.id}, -1)">−</button>
                            <b>${item.quantity}</b>
                            <button type="button" onclick="changeDish(${item.id}, 1)">＋</button>
                        </div>
                        <div class="cart-popup-item-notes">
                            <textarea 
                                placeholder="Notas especiales (ej: sin cebolla, término medio...)" 
                                maxlength="500"
                                oninput="updateNotes(${item.id}, this.value)"
                                class="cart-notes-input"
                            >${item.notas || ''}</textarea>
                        </div>
                    </div>
                `).join('');
            }

            const total = items.reduce((sum, item) => sum + item.price * item.quantity, 0);
            const totalEl = document.getElementById('cart-total-val');
            if (totalEl) totalEl.textContent = '$' + total.toLocaleString('es-CO');

            document.getElementById('item-inputs').innerHTML = items.map((item, index) => `
                <input type="hidden" name="items[${index}][id_plato]" value="${item.id}">
                <input type="hidden" name="items[${index}][cantidad]" value="${item.quantity}">
                <input type="hidden" name="items[${index}][notas_especiales]" value="${item.notas || ''}">
            `).join('');
        }

        function updateCartBadge() {
            const count = Object.values(cartItems).reduce((sum, item) => sum + item.quantity, 0);
            const badge = document.getElementById('fab-badge');
            if (badge) {
                badge.textContent = count;
                badge.style.display = count > 0 ? 'flex' : 'none';
            }
        }

        /* CLIENT MODAL */
        function openClientModal() {
            document.getElementById('client-modal').classList.add('is-open');
        }
        function closeClientModal() {
            document.getElementById('client-modal').classList.remove('is-open');
        }
        function selectClient(id, name, element) {
            document.querySelectorAll('.client-option').forEach(opt => opt.classList.remove('is-selected'));
            element.classList.add('is-selected');
            tempClientId = id;
            tempClientName = name;
        }
        function confirmClient() {
            document.getElementById('selected-client-id').value = tempClientId;
            const nameEl = document.getElementById('display-client-name');
            if (nameEl) nameEl.textContent = tempClientName || 'Sin asignar';
            closeClientModal();
        }
        function clearClient() {
            tempClientId = '';
            tempClientName = 'Sin asignar';
            document.querySelectorAll('.client-option').forEach(opt => opt.classList.remove('is-selected'));
            document.getElementById('selected-client-id').value = '';
            const nameEl = document.getElementById('display-client-name');
            if (nameEl) nameEl.textContent = 'Sin asignar';
            closeClientModal();
        }
        function filterClients() {
            const query = document.getElementById('client-search-input').value.toLowerCase();
            document.querySelectorAll('.client-option').forEach(opt => {
                opt.style.display = (opt.dataset.name || '').includes(query) ? 'flex' : 'none';
            });
        }
        function filterDishes() {
            const query = document.getElementById('search').value.toLowerCase();
            document.querySelectorAll('.dish').forEach(dish => {
                dish.style.display = dish.dataset.name.includes(query) ? 'flex' : 'none';
            });
        }
        function filterCategory(cat, el) {
            document.querySelectorAll('.category span').forEach(s => s.classList.remove('active'));
            el.classList.add('active');
            document.querySelectorAll('.dish').forEach(dish => {
                dish.style.display = (cat === 'todos' || dish.dataset.category === cat) ? 'flex' : 'none';
            });
        }
        function filterStatus(status, el) {
            document.querySelectorAll('.tabs .tab').forEach(t => t.classList.remove('active'));
            el.classList.add('active');
            document.querySelectorAll('.order-row').forEach(row => {
                row.style.display = (status === 'todos' || row.dataset.status === status) ? 'grid' : 'none';
            });
        }
        function toggleProfileMenu() {
            const menu = document.querySelector('.profile-menu');
            const button = menu.querySelector('.profile');
            menu.classList.toggle('is-open');
            button.setAttribute('aria-expanded', menu.classList.contains('is-open'));
        }
        document.addEventListener('click', event => {
            if (!event.target.closest('.profile-menu')) {
                document.querySelector('.profile-menu')?.classList.remove('is-open');
            }
        });

        /* CONFIRM CREATE ORDER MODAL */
        function confirmCreateOrder() {
            const items = Object.values(cartItems);
            const count = items.reduce((sum, item) => sum + item.quantity, 0);
            
            if (count === 0) {
                alert('Debes agregar al menos un plato al pedido');
                return;
            }
            
            document.getElementById('confirm-create-text').textContent = `Se creara un pedido con ${count} plato(s).`;
            document.getElementById('confirm-create-modal').classList.add('is-open');
        }
        
        function closeConfirmCreate() {
            document.getElementById('confirm-create-modal').classList.remove('is-open');
        }
        
        function submitCreateOrder() {
            document.getElementById('order-form').submit();
        }

        /* ACCOUNT MODAL */
        let currentOrderId = null;
        let currentOrderState = null;
        let currentOrderItems = [];
        
        function openAccountModal(orderId, clientName, estado, total, items) {
            currentOrderId = orderId;
            currentOrderState = estado;
            currentOrderItems = items;
            
            document.getElementById('account-pedido').textContent = '#' + orderId;
            document.getElementById('account-cliente').textContent = clientName;
            
            const estadoEl = document.getElementById('account-estado');
            estadoEl.textContent = estado.charAt(0).toUpperCase() + estado.slice(1);
            estadoEl.className = 'status ' + estado.replace(' ', '-');
            
            document.getElementById('account-total-val').textContent = '$' + total.toLocaleString('es-CO');
            
            const itemsHtml = items.map(item => `
                <div class="account-item">
                    <div><strong>${item.nombre}</strong><span>${item.cantidad} u.</span></div>
                    <b>$${(item.precio * item.cantidad).toLocaleString('es-CO')}</b>
                    <button type="button" class="account-item-delete" onclick="removeItemFromOrder('${item.nombre}')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    </button>
                </div>
            `).join('');
            document.getElementById('account-items').innerHTML = itemsHtml;
            
            const addSection = document.getElementById('account-add-section');
            if (estado === 'pendiente') {
                addSection.style.display = 'block';
            } else {
                addSection.style.display = 'none';
            }
            
            document.getElementById('account-modal').classList.add('is-open');
        }
        
        function closeAccountModal() {
            document.getElementById('account-modal').classList.remove('is-open');
        }
        
        function addDishToOrder() {
            const select = document.getElementById('account-plato-select');
            const quantity = document.getElementById('account-quantity-input').value;
            
            if (!select.value) {
                alert('Selecciona un plato');
                return;
            }
            
            const option = select.options[select.selectedIndex];
            const platoId = option.value;
            const platoName = option.dataset.name;
            const price = parseFloat(option.dataset.price);
            
            // Enviar request al servidor para añadir el plato
            fetch(`/admin/pedidos/${currentOrderId}/agregar-item`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    id_plato: platoId,
                    cantidad: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error al agregar el plato');
                }
            });
        }
        
        function removeItemFromOrder(itemName) {
            if (!confirm(`¿Eliminar ${itemName} del pedido?`)) return;
            
            // Aquí se implementaría la lógica para eliminar item del pedido
            alert('Funcionalidad de eliminar item en desarrollo');
        }

        /* ORDER ACTIONS */
        function sendToKitchen(orderId) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/pedidos/${orderId}/estado`;
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            
            const estadoInput = document.createElement('input');
            estadoInput.type = 'hidden';
            estadoInput.name = 'estado';
            estadoInput.value = 'en preparación';
            
            const returnInput = document.createElement('input');
            returnInput.type = 'hidden';
            returnInput.name = 'return_to';
            returnInput.value = 'mesero';
            
            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            form.appendChild(estadoInput);
            form.appendChild(returnInput);
            document.body.appendChild(form);
            form.submit();
        }
        
        function deliverOrder(orderId) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/pedidos/${orderId}/estado`;
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            
            const estadoInput = document.createElement('input');
            estadoInput.type = 'hidden';
            estadoInput.name = 'estado';
            estadoInput.value = 'entregado';
            
            const returnInput = document.createElement('input');
            returnInput.type = 'hidden';
            returnInput.name = 'return_to';
            returnInput.value = 'mesero';
            
            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            form.appendChild(estadoInput);
            form.appendChild(returnInput);
            document.body.appendChild(form);
            form.submit();
        }
        
        function cancelOrder(orderId) {
            if (!confirm('¿Estás seguro de cancelar este pedido?')) return;
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/pedidos/${orderId}/estado`;
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            
            const estadoInput = document.createElement('input');
            estadoInput.type = 'hidden';
            estadoInput.name = 'estado';
            estadoInput.value = 'cancelado';
            
            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            form.appendChild(estadoInput);
            document.body.appendChild(form);
            form.submit();
        }

        /* CANCEL CONFIRMATION MODAL */
        let orderToCancel = null;
        
        function confirmCancelOrder(orderId) {
            orderToCancel = orderId;
            document.getElementById('cancel-modal').classList.add('is-open');
        }
        
        function closeCancelModal() {
            orderToCancel = null;
            document.getElementById('cancel-modal').classList.remove('is-open');
        }
        
        function executeCancelOrder() {
            if (!orderToCancel) return;
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/pedidos/${orderToCancel}/cancelar`;
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            
            form.appendChild(csrfInput);
            document.body.appendChild(form);
            form.submit();
        }

        /* PAYMENT MODAL */
        let currentPaymentOrderId = null;
        
        function openPaymentModal(orderId, amount) {
            currentPaymentOrderId = orderId;
            document.getElementById('payment-pedido-id').textContent = orderId;
            document.getElementById('payment-amount').value = amount;
            document.getElementById('payment-form').action = `/admin/pedidos/${orderId}/pago`;
            document.getElementById('payment-modal').classList.add('is-open');
        }
        
        function closePaymentModal() {
            currentPaymentOrderId = null;
            document.getElementById('payment-modal').classList.remove('is-open');
        }

        /* SUCCESS MODAL ON PAGE LOAD */
        @if(session('pedido_creado'))
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                showSuccessModal({{ session('pedido_creado') }});
            }, 300);
        });
        @endif

        @if(session('estado_actualizado'))
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                showStatusUpdatedModal({{ session('estado_actualizado')['pedido_id'] }}, '{{ session('estado_actualizado')['estado'] }}');
            }, 300);
        });
        @endif
        
        function showSuccessModal(orderId) {
            const modal = document.createElement('div');
            modal.className = 'success-modal-overlay is-open';
            modal.innerHTML = `
                <div class="success-modal-card">
                    <div class="success-modal-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="9 12 11 14 15 10"/>
                        </svg>
                    </div>
                    <h3>Pedido Creado</h3>
                    <p>Pedido #${orderId} creado correctamente.</p>
                    <button type="button" class="btn-success-ok" onclick="this.closest('.success-modal-overlay').remove()">OK</button>
                </div>
            `;
            document.body.appendChild(modal);
        }

        function showStatusUpdatedModal(orderId, estado) {
            document.getElementById('status-updated-text').textContent = `Pedido #${orderId} marcado como ${estado}.`;
            document.getElementById('status-updated-modal').classList.add('is-open');
        }

        function closeStatusUpdatedModal() {
            document.getElementById('status-updated-modal').classList.remove('is-open');
        }
    </script>
</body>
</html>
