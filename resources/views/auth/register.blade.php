<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Crea tu cuenta en El Cielo — Centro Vacacional y Recreacional">
    <title>Crear una cuenta — El Cielo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/register/register.css') }}?v={{ time() }}">
</head>

<body>
<div class="auth-layout">

    {{-- ============================================================
         PANEL IZQUIERDO — imagen + branding (visible en desktop)
         ============================================================ --}}
    <aside class="auth-panel" aria-hidden="true">
        <div class="auth-panel__bg"></div>
        <div class="auth-panel__content">

            <div class="auth-panel__quote">
                <h2 class="auth-panel__quote-text">
                    El Cielo
                </h2>
                <p class="auth-panel__quote-sub">
                    Únete a nuestra familia y descubre los sabores más exquisitos.
                </p>
            </div>

        </div>
    </aside>

    {{-- ============================================================
         PANEL DERECHO — formulario
         ============================================================ --}}
    <main class="auth-form-side" role="main">
        <div class="auth-card">

            {{-- Cabecera --}}
            <div class="auth-card__head">
                <p class="auth-card__eyebrow">CENTRO VACACIONAL EL CIELO</p>
                <h1 class="auth-card__title">Crear una cuenta</h1>
            </div>

            {{-- Alerta de éxito --}}
            @if(session('success'))
                <div class="auth-alert auth-alert--success" role="alert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="9 12 11 14 15 10"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Alerta de errores --}}
            @if($errors->any())
                <div class="auth-alert auth-alert--error" role="alert" aria-live="assertive">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
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

            {{-- Formulario --}}
            <form method="POST" action="{{ route('register.store') }}" class="auth-form" novalidate>
                @csrf

                {{-- Nombre completo --}}
                <div class="field">
                    <label for="nombre" class="field__label">Nombre completo</label>
                    <div class="field__input-wrap">
                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            value="{{ old('nombre') }}"
                            placeholder="Escribe tu nombre"
                            class="field__input"
                            autocomplete="name"
                            required
                            autofocus
                        >
                    </div>
                </div>

                {{-- Correo electrónico --}}
                <div class="field">
                    <label for="correo" class="field__label">Correo electrónico</label>
                    <div class="field__input-wrap">
                        <input
                            type="email"
                            id="correo"
                            name="correo"
                            value="{{ old('correo') }}"
                            placeholder="usuario@ejemplo.com"
                            class="field__input"
                            autocomplete="email"
                            required
                        >
                    </div>
                </div>

                {{-- Teléfono --}}
                <div class="field">
                    <label for="telefono" class="field__label">Número telefónico</label>
                    <div class="field__input-wrap">
                        <input
                            type="tel"
                            id="telefono"
                            name="telefono"
                            value="{{ old('telefono') }}"
                            placeholder="Ej. 300 123 4567"
                            class="field__input"
                            autocomplete="tel"
                        >
                    </div>
                </div>

                {{-- Contraseña --}}
                <div class="field">
                    <label for="contrasena" class="field__label">Contraseña</label>
                    <div class="field__input-wrap">
                        <input
                            type="password"
                            id="contrasena"
                            name="contrasena"
                            placeholder="••••••••"
                            class="field__input field__input--has-toggle"
                            autocomplete="new-password"
                            required
                            minlength="6"
                        >
                        <button
                            type="button"
                            class="field__toggle"
                            aria-label="Mostrar contraseña"
                            onclick="togglePassword(this)"
                        >
                            <svg id="eye-show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg id="eye-hide" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                                <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                    <p class="field__helper">• Mínimo 6 caracteres</p>
                </div>

                {{-- Botón Registrarse --}}
                <button type="submit" class="auth-submit">
                    <span>Registrarse</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <line x1="19" y1="8" x2="19" y2="14"/>
                        <line x1="22" y1="11" x2="16" y2="11"/>
                    </svg>
                </button>

            </form>

            {{-- Links secundarios --}}
            <div class="auth-links">
                <a href="{{ route('login') }}" class="auth-link">
                    ¿Ya tienes una cuenta?&nbsp;<span class="highlight">Inicia sesión aquí</span>
                </a>
            </div>

        </div>
    </main>

</div>

<script>
function togglePassword(btn) {
    const input  = btn.closest('.field__input-wrap').querySelector('.field__input');
    const eyeShow = btn.querySelector('#eye-show');
    const eyeHide = btn.querySelector('#eye-hide');
    if (input.type === 'password') {
        input.type = 'text';
        eyeShow.style.display = 'none';
        eyeHide.style.display = 'block';
        btn.setAttribute('aria-label', 'Ocultar contraseña');
    } else {
        input.type = 'password';
        eyeShow.style.display = 'block';
        eyeHide.style.display = 'none';
        btn.setAttribute('aria-label', 'Mostrar contraseña');
    }
}
</script>
</body>
</html>
