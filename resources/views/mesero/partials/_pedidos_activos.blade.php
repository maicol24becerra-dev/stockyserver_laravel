{{-- SECCIÓN PEDIDOS ACTIVOS --}}
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
