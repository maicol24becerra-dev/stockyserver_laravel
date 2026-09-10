<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Recuperar contraseña en El Cielo — Centro Vacacional y Recreacional">
    <title>Recuperar contraseña — El Cielo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/forgot-password/forgot-password.css') }}?v={{ time() }}">
</head>
<body>

{{-- Fondo y Viñeta --}}
<div class="auth-backdrop" aria-hidden="true"></div>
<div class="auth-overlay" aria-hidden="true"></div>

{{-- MODAL DE ERROR ESTILO POPUP --}}
@if($errors->any())
    <div class="modal-overlay" id="errorModal">
        <div class="modal-card">
            <div class="modal-icon-error">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </div>

            <h2 class="modal-title">
                @if(str_contains(strtolower($errors->first()), 'trabajador') || str_contains(strtolower($errors->first()), 'personal') || str_contains(strtolower($errors->first()), 'cliente'))
                    Acceso restringido
                @else
                    Correo no encontrado
                @endif
            </h2>

            <p class="modal-message">
                {{ $errors->first() }}
            </p>

            <button type="button" class="btn-modal-ok" onclick="closeModal()">
                OK
            </button>
        </div>
    </div>
@endif

<div class="auth-container">
    <div class="auth-card">

        {{-- Icono de Llave --}}
        <div class="icon-badge">
            <svg viewBox="0 0 24 24">
                <path d="M7 14A5 5 0 0 1 2 9a5 5 0 0 1 5-5c2.3 0 4.28 1.56 4.82 3.69L19.5 7.5l.5.5v2h-2v2h-2v2h-2.18A5.002 5.002 0 0 1 7 14zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6zm0 2a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </svg>
        </div>

        {{-- Título y Descripción --}}
        <h1 class="auth-title">¿Olvidaste tu contraseña?</h1>
        <p class="auth-description">
            Ingresa la dirección de correo electrónico asociada a tu cuenta y te enviaremos un código para restablecerla.
        </p>

        {{-- Alerta de Éxito --}}
        @if(session('success'))
            <div class="auth-alert auth-alert--success" role="alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="9 12 11 14 15 10"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Formulario --}}
        <form method="POST" action="{{ route('password.email') }}" class="auth-form" novalidate>
            @csrf

            <div class="field">
                <input
                    type="email"
                    id="correo"
                    name="correo"
                    value="{{ old('correo') }}"
                    placeholder="ejemplo@correo.com"
                    class="field__input"
                    autocomplete="email"
                    required
                    autofocus
                >
            </div>

            <button type="submit" class="auth-submit">
                <span>Enviar Código</span>
                <svg viewBox="0 0 24 24">
                    <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                </svg>
            </button>
        </form>

        {{-- Enlace Volver --}}
        <a href="{{ route('login') }}" class="auth-back-link">
            ← Volver al inicio de sesión
        </a>

    </div>
</div>

<script>
function closeModal() {
    const modal = document.getElementById('errorModal');
    if (modal) {
        modal.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
        modal.style.opacity = '0';
        setTimeout(() => {
            modal.style.display = 'none';
        }, 200);
    }
}
</script>
</body>
</html>
