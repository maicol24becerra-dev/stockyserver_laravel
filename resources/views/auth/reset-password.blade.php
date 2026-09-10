<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Establece tu nueva contraseña en El Cielo">
    <title>Nueva contraseña — El Cielo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/forgot-password/forgot-password.css') }}?v={{ time() }}">
</head>
<body>

{{-- Fondo y Viñeta --}}
<div class="auth-backdrop" aria-hidden="true"></div>
<div class="auth-overlay" aria-hidden="true"></div>

<div class="auth-container">
    <div class="auth-card">

        {{-- Icono de Llave --}}
        <div class="icon-badge">
            <svg viewBox="0 0 24 24">
                <path d="M7 14A5 5 0 0 1 2 9a5 5 0 0 1 5-5c2.3 0 4.28 1.56 4.82 3.69L19.5 7.5l.5.5v2h-2v2h-2v2h-2.18A5.002 5.002 0 0 1 7 14zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6zm0 2a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </svg>
        </div>

        {{-- Título y Descripción --}}
        <h1 class="auth-title">Nueva contraseña</h1>
        <p class="auth-description">
            Ingresa el código que te enviamos y define tu nueva contraseña de acceso.
        </p>

        @if(session('success'))
            <div class="auth-alert auth-alert--success" role="alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="9 12 11 14 15 10"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('codigo'))
            <div class="recovery-code-box">
                <strong>Tu código de recuperación es:</strong>
                <div class="code-number">{{ session('codigo') }}</div>
                <small>Este código es válido durante 15 minutos.</small>
            </div>
        @endif

        @if($errors->any())
            <div class="auth-alert auth-alert--error" role="alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <ul class="auth-alert__list">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="auth-form" novalidate>
            @csrf

            <div class="field">
                <input
                    type="email"
                    id="correo"
                    name="correo"
                    value="{{ old('correo', $correo) }}"
                    placeholder="Correo electrónico"
                    class="field__input"
                    required
                >
            </div>

            <div class="field">
                <input
                    type="text"
                    id="codigo"
                    name="codigo"
                    maxlength="6"
                    placeholder="Código de 6 dígitos"
                    class="field__input"
                    style="letter-spacing: 2px; font-weight: 700;"
                    required
                >
            </div>

            <div class="field">
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Nueva contraseña (mínimo 8 caracteres)"
                    class="field__input"
                    minlength="8"
                    required
                >
            </div>

            <div class="field">
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Confirmar nueva contraseña"
                    class="field__input"
                    minlength="8"
                    required
                >
            </div>

            <button type="submit" class="auth-submit">
                <span>Cambiar contraseña</span>
            </button>
        </form>

        <a href="{{ route('login') }}" class="auth-back-link">
            ← Volver al inicio de sesión
        </a>

    </div>
</div>

</body>
</html>
