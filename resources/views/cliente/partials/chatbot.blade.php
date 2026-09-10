{{-- CHATBOT FLOATING CONTAINER & MODAL --}}
<div id="chatbotModal" class="chatbot-modal">
    {{-- HEADER --}}
    <div class="chatbot-header">
        <div class="chatbot-header-info">
            <div class="chatbot-avatar-circle">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="10" rx="2"/>
                    <circle cx="12" cy="5" r="2"/>
                    <path d="M12 7v4"/>
                    <line x1="8" y1="16" x2="8.01" y2="16"/>
                    <line x1="16" y1="16" x2="16.01" y2="16"/>
                </svg>
            </div>
            <div>
                <strong class="chatbot-title">Asistente El Cielo</strong>
                <span class="chatbot-status"><span class="status-dot"></span>En línea · Respuesta inmediata</span>
            </div>
        </div>
        <button id="closeChatbotBtn" class="chatbot-close-btn">&times;</button>
    </div>

    {{-- BODY --}}
    <div class="chatbot-body" id="chatbotBody">
        {{-- GREETING BUBBLE --}}
        <div class="chat-message bot">
            <div class="message-avatar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="11" width="18" height="10" rx="2"/>
                    <circle cx="12" cy="5" r="2"/>
                    <path d="M12 7v4"/>
                </svg>
            </div>
            <div class="message-content">
                ¡Hola, <strong>{{ auth()->user()->nombre ?? 'Maicol' }}</strong>! 👋 Soy el asistente de <strong>El Cielo</strong>. ¿En qué te puedo ayudar hoy?
            </div>
        </div>

        {{-- QUICK OPTIONS --}}
        <div class="chat-options-container" id="chatOptionsContainer">
            <button class="chat-option-btn" onclick="sendChatQuery('menu')">
                🍽️ Información del menú
            </button>
            <button class="chat-option-btn" onclick="sendChatQuery('estado')">
                📦 Estado de mi pedido
            </button>
            <button class="chat-option-btn" onclick="sendChatQuery('pago')">
                💳 Métodos de pago
            </button>
            <button class="chat-option-btn" onclick="sendChatQuery('horarios')">
                🕒 Horarios y ubicación
            </button>
            <button class="chat-option-btn" onclick="sendChatQuery('servicios')">
                🌊 Servicios del centro
            </button>
            <button class="chat-option-btn" onclick="sendChatQuery('faq')">
                ❓ Preguntas frecuentes
            </button>
            <button class="chat-option-btn" onclick="sendChatQuery('queja')">
                📝 Enviar una queja
            </button>
        </div>
    </div>
</div>

{{-- FLOATING TOGGLE BUTTON --}}
<button class="floating-chat-btn" id="toggleChatbotBtn" title="Asistencia en vivo">
    <svg id="chatIconOpen" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
    </svg>
    <svg id="chatIconClose" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
        <line x1="18" y1="6" x2="6" y2="18"/>
        <line x1="6" y1="6" x2="18" y2="18"/>
    </svg>
    <span class="chat-badge" id="chatBadge">1</span>
</button>

<style>
/* ── CHATBOT MODAL ── */
.chatbot-modal {
    position: fixed;
    bottom: 90px;
    right: 24px;
    width: 360px;
    max-width: calc(100vw - 32px);
    height: 520px;
    max-height: calc(100vh - 120px);
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.18);
    display: none;
    flex-direction: column;
    overflow: hidden;
    z-index: 1000;
    border: 1px solid #e2ece6;
    animation: slideUp 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}

.chatbot-modal.show {
    display: flex;
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(20px) scale(0.95); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

.chatbot-header {
    background: #013726;
    padding: 16px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: #ffffff;
}

.chatbot-header-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.chatbot-avatar-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #00aeef;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.chatbot-title {
    display: block;
    font-size: 1rem;
    font-weight: 800;
    line-height: 1.2;
}

.chatbot-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.68rem;
    color: #38bdf8;
    font-weight: 600;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #4ade80;
}

.chatbot-close-btn {
    background: none;
    border: none;
    color: rgba(255, 255, 255, 0.7);
    font-size: 1.5rem;
    cursor: pointer;
    line-height: 1;
    transition: color 0.2s;
}

.chatbot-close-btn:hover {
    color: #ffffff;
}

.chatbot-body {
    padding: 18px;
    flex: 1;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 14px;
    background: #f8fbf9;
}

.chat-message {
    display: flex;
    gap: 10px;
    align-items: flex-start;
}

.chat-message.bot .message-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #00aeef;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.chat-message.user {
    justify-content: flex-end;
}

.chat-message.bot .message-content {
    background: #ffffff;
    border: 1px solid #d4ece0;
    border-radius: 18px 18px 18px 4px;
    padding: 12px 14px;
    font-size: 0.88rem;
    color: #1e293b;
    line-height: 1.45;
    max-width: 85%;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}

.chat-message.user .message-content {
    background: #046242;
    color: #ffffff;
    border-radius: 18px 18px 4px 18px;
    padding: 10px 14px;
    font-size: 0.88rem;
    font-weight: 600;
    max-width: 85%;
}

.chat-options-container {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 6px;
}

.chat-option-btn {
    background: #ffffff;
    border: 1.5px solid #16a34a;
    color: #065f46;
    padding: 8px 14px;
    border-radius: 25px;
    font-size: 0.82rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
    font-family: inherit;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.chat-option-btn:hover {
    background: #e6f4ea;
    transform: translateY(-1px);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('chatbotModal');
    const toggleBtn = document.getElementById('toggleChatbotBtn');
    const closeBtn = document.getElementById('closeChatbotBtn');
    const iconOpen = document.getElementById('chatIconOpen');
    const iconClose = document.getElementById('chatIconClose');
    const badge = document.getElementById('chatBadge');

    function toggleChat() {
        const isShow = modal.classList.toggle('show');
        iconOpen.style.display = isShow ? 'none' : 'block';
        iconClose.style.display = isShow ? 'block' : 'none';
        if (isShow && badge) {
            badge.style.display = 'none';
        }
    }

    if (toggleBtn) toggleBtn.addEventListener('click', toggleChat);
    if (closeBtn) closeBtn.addEventListener('click', toggleChat);
});

function sendChatQuery(type) {
    const body = document.getElementById('chatbotBody');
    let userText = '';
    let botReply = '';

    switch(type) {
        case 'menu':
            userText = '🍽️ Información del menú';
            botReply = 'Puedes explorar nuestro menú completo ingresando a la sección <strong>Explorar Menú</strong> en la barra lateral. ¡Tenemos pescados, carnes, postres y bebidas frescas!';
            break;
        case 'estado':
            userText = '📦 Estado de mi pedido';
            botReply = 'Para revisar el progreso de tus pedidos en tiempo real (Pendiente, En preparación, Listo o Entregado), ingresa a la sección <strong>Mis Pedidos</strong>.';
            break;
        case 'pago':
            userText = '💳 Métodos de pago';
            botReply = 'Aceptamos pagos en efectivo, tarjetas de crédito/débito, Nequi y Daviplata en el área de recepción o directo con tu mesero.';
            break;
        case 'horarios':
            userText = '🕒 Horarios y ubicación';
            botReply = 'El restaurante y bar de <strong>El Cielo</strong> atiende todos los días de <strong>8:00 AM a 10:00 PM</strong> en la zona central del resort.';
            break;
        case 'servicios':
            userText = '🌊 Servicios del centro';
            botReply = 'Contamos con piscinas resort, servicio a la mesa, área recreativa de playa y bar de cócteles tropicales.';
            break;
        case 'faq':
            userText = '❓ Preguntas frecuentes';
            botReply = '¿Tienes alguna duda sobre reservas o platos especiales? Puedes hablar directamente con nuestro personal de meseros.';
            break;
        case 'queja':
            userText = '📝 Enviar una queja';
            botReply = 'Lamentamos cualquier inconveniente. Por favor comunica tus observaciones al administrador para brindarte atención prioritaria.';
            break;
    }

    // Append User Message
    const userMsg = document.createElement('div');
    userMsg.className = 'chat-message user';
    userMsg.innerHTML = `<div class="message-content">${userText}</div>`;
    body.appendChild(userMsg);

    // Scroll to bottom
    body.scrollTop = body.scrollHeight;

    // Append Bot Reply with delay
    setTimeout(() => {
        const botMsg = document.createElement('div');
        botMsg.className = 'chat-message bot';
        botMsg.innerHTML = `
            <div class="message-avatar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="11" width="18" height="10" rx="2"/>
                    <circle cx="12" cy="5" r="2"/>
                    <path d="M12 7v4"/>
                </svg>
            </div>
            <div class="message-content">${botReply}</div>
        `;
        body.appendChild(botMsg);
        body.scrollTop = body.scrollHeight;
    }, 400);
}
</script>
