{{-- BOTÓN FLOTANTE (FAB) Y SCRIPTS MESERO --}}
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
        const price = parseFloat(option.dataset.price);
        
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
