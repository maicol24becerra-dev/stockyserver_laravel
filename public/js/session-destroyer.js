/**
 * Session Destroyer
 * Destruye la sesión cuando el usuario intenta navegador hacia atrás
 */

(function() {
    'use strict';

    // Configuración
    const config = {
        checkInterval: 10000, // Verificar sesión cada 10 segundos
        logoutTimeout: 3000,  // Redirigir después de 3 segundos
        storageKey: 'session_load_count'
    };

    // Obtener CSRF token
    function getCsrfToken() {
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        if (!token) {
            console.warn('CSRF token no encontrado');
        }
        return token || '';
    }

    // Mostrar pantalla de sesión cerrada
    function showSessionClosedScreen() {
        const html = `
            <div style="
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
                width: 100vw;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                margin: 0;
                padding: 0;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
                position: fixed;
                top: 0;
                left: 0;
                z-index: 999999;
            ">
                <div style="
                    background: white;
                    padding: 40px;
                    border-radius: 12px;
                    text-align: center;
                    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                    max-width: 400px;
                    animation: slideUp 0.3s ease-out;
                ">
                    <h1 style="color: #dc2626; margin: 0; font-size: 28px;">🔒 Sesión Cerrada</h1>
                    <p style="color: #666; margin: 15px 0 0 0; font-size: 16px; line-height: 1.5;">
                        No puedes navegar hacia atrás por razones de seguridad. Tu sesión ha sido cerrada.
                    </p>
                    <button onclick="window.location.href='/login'" style="
                        margin-top: 20px;
                        padding: 12px 32px;
                        background: #2563eb;
                        color: white;
                        border: none;
                        border-radius: 6px;
                        cursor: pointer;
                        font-weight: 600;
                        font-size: 14px;
                        transition: background 0.2s;
                    " onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">
                        Ir a Login
                    </button>
                </div>
                <style>
                    @keyframes slideUp {
                        from {
                            opacity: 0;
                            transform: translateY(20px);
                        }
                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }
                </style>
            </div>
        `;
        
        // Reemplazar todo el contenido del body
        document.documentElement.innerHTML = html;
    }

    // Destruir sesión en servidor
    function destroySessionOnServer() {
        return fetch('/logout', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            credentials: 'same-origin',
            body: JSON.stringify({})
        })
        .then(response => {
            console.log('Logout response:', response.status);
            return response;
        })
        .catch(error => {
            console.error('Error al cerrar sesión:', error);
        });
    }

    // Manejar intento de ir atrás
    function handleBackButtonAttempt() {
        console.log('Intento de navegación hacia atrás detectado');
        
        // 1. Mostrar pantalla inmediatamente
        showSessionClosedScreen();
        
        // 2. Destruir sesión en servidor
        destroySessionOnServer();
        
        // 3. Redirigir a login después del timeout
        setTimeout(() => {
            window.location.href = '/login';
        }, config.logoutTimeout);
    }

    // Verificar sesión
    function checkSession() {
        fetch('/check-session', {
            method: 'GET',
            credentials: 'same-origin'
        })
        .then(response => {
            if (response.status === 401) {
                console.log('Sesión expirada detectada');
                showSessionClosedScreen();
                setTimeout(() => {
                    window.location.href = '/login';
                }, config.logoutTimeout);
            }
        })
        .catch(error => {
            console.error('Error verificando sesión:', error);
        });
    }

    // Inicializar
    function init() {
        console.log('Session Destroyer inicializado');

        // Obtener o crear contador de carga
        let loadCount = parseInt(sessionStorage.getItem(config.storageKey) || '0') + 1;
        sessionStorage.setItem(config.storageKey, loadCount);
        
        // Crear punto de historial
        history.pushState({loadCount: loadCount}, null, location.href);
        
        // Detector de botón atrás
        window.addEventListener('popstate', function(event) {
            handleBackButtonAttempt();
            // Intentar push state nuevamente para evitar más intentos
            history.pushState({loadCount: loadCount}, null, location.href);
        });

        // Verificar sesión periódicamente
        setInterval(checkSession, config.checkInterval);

        // Verificar sesión al cargar (después de 1 segundo)
        setTimeout(checkSession, 1000);
    }

    // Iniciar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
