<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Crear cuenta - StockYServe</title>

    <link rel="stylesheet" href="{{ asset('css/register.css') }}">

</head>


<body>

    <aside class="register-panel" aria-hidden="true">
        <div class="register-panel__content">
            <h2>El Cielo</h2>
            <p>Únete a nuestra familia y descubre los sabores<br>más exquisitos.</p>
        </div>
    </aside>


    {{-- ========================================================= --}}
    {{-- ENCABEZADO --}}
    {{-- ========================================================= --}}

    <header class="header">

        <a
            href="{{ route('home') }}"
            class="logo"
        >
            <img src="{{ asset('images/logo.png') }}" alt="El Cielo" onerror="this.style.display='none';this.nextElementSibling.style.display='inline-flex';">
            <span class="logo-mark" style="display:none;">EC</span>
            <span>El Cielo</span>
        </a>

        <a
            href="{{ route('home') }}"
            class="home-button"
        >
            ← Volver al inicio
        </a>

    </header>


    {{-- ========================================================= --}}
    {{-- REGISTRO --}}
    {{-- ========================================================= --}}

    <main class="container">

        <div class="card">

            <div class="auth-card__eyebrow">CENTRO VACACIONAL EL CIELO</div>
            <h1>
                Crear una cuenta
            </h1>

            <p class="description">
                Regístrate en StockYServe como cliente.
            </p>


            {{-- ===================================================== --}}
            {{-- MENSAJE DE ÉXITO --}}
            {{-- ===================================================== --}}

            @if(session('success'))

                <div class="success">
                    {{ session('success') }}
                </div>

            @endif


            {{-- ===================================================== --}}
            {{-- ERRORES --}}
            {{-- ===================================================== --}}

            @if($errors->any())

                <div class="error">

                    <ul>

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- ===================================================== --}}
            {{-- FORMULARIO --}}
            {{-- ===================================================== --}}

            <form
                method="POST"
                action="{{ route('register.store') }}"
            >

                @csrf


                {{-- ================================================= --}}
                {{-- NOMBRE --}}
                {{-- ================================================= --}}

                <div class="form-group">

                    <label for="nombre">
                        Nombre completo
                    </label>

                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        value="{{ old('nombre') }}"
                        required
                        autofocus
                    >

                </div>


                {{-- ================================================= --}}
                {{-- CORREO --}}
                {{-- ================================================= --}}

                <div class="form-group">

                    <label for="correo">
                        Correo electrónico
                    </label>

                    <input
                        type="email"
                        id="correo"
                        name="correo"
                        value="{{ old('correo') }}"
                        required
                    >

                </div>


                {{-- ================================================= --}}
                {{-- TELÉFONO --}}
                {{-- ================================================= --}}

                <div class="form-group">

                    <label for="telefono">
                        Teléfono
                    </label>

                    <input
                        type="text"
                        id="telefono"
                        name="telefono"
                        value="{{ old('telefono') }}"
                        required
                    >

                </div>


                {{-- ================================================= --}}
                {{-- CONTRASEÑA --}}
                {{-- ================================================= --}}

                <div class="form-group">

                    <label for="contrasena">
                        Contraseña
                    </label>

                    <input
                        type="password"
                        id="contrasena"
                        name="contrasena"
                        required
                        minlength="8"
                        autocomplete="new-password"
                    >

                    @error('contrasena')

                        <small style="color: #dc3545;">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- CONFIRMAR CONTRASEÑA --}}
                {{-- ================================================= --}}

                <div class="form-group">

                    <label for="contrasena_confirmation">
                        Confirmar contraseña
                    </label>

                    <input
                        type="password"
                        id="contrasena_confirmation"
                        name="contrasena_confirmation"
                        required
                        minlength="8"
                        autocomplete="new-password"
                    >

                    @error('contrasena_confirmation')

                        <small style="color: #dc3545;">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- CREAR CUENTA --}}
                {{-- ================================================= --}}

                <button
                    type="submit"
                    class="button-register"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    Registrarse
                </button>

            </form>


            {{-- ===================================================== --}}
            {{-- VOLVER AL INICIO --}}
            {{-- ===================================================== --}}

            <a
                href="{{ route('home') }}"
                class="button-home"
            >
                ← Volver al inicio
            </a>


            {{-- ===================================================== --}}
            {{-- INICIAR SESIÓN --}}
            {{-- ===================================================== --}}

            <div class="login-link">

                ¿Ya tienes una cuenta?

                <a href="{{ route('login') }}">
                    Iniciar sesión
                </a>

            </div>

        </div>

    </main>


    {{-- ========================================================= --}}
    {{-- FOOTER --}}
    {{-- ========================================================= --}}

    <footer class="footer">

        © {{ date('Y') }} StockYServe.
        Todos los derechos reservados.

    </footer>


</body>

</html>
