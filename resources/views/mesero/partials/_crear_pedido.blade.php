{{-- SECCIÓN CREAR PEDIDO --}}
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
