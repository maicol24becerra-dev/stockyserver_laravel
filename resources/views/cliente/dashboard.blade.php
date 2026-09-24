<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Panel — El Cielo</title>
    <link rel="icon" href="{{ asset('images/logo-circle.png') }}?v={{ time() }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cliente/cliente-dashboard.css') }}?v={{ time() }}">
</head>
<body>

<!-- Session Destroyer Script -->
<script src="{{ asset('js/session-destroyer.js') }}"></script>

    {{-- BLOQUE 1: SIDEBAR CLIENTE --}}
    @include('cliente.partials._sidebar')

    {{-- CONTENEDOR PRINCIPAL --}}
    <div class="main-wrapper">

        {{-- BLOQUE 2: BARRA SUPERIOR CLIENTE --}}
        @include('cliente.partials._topbar')

        {{-- CONTENIDO PRINCIPAL --}}
        <main class="main-content">
            {{-- BLOQUE 3: HISTORIAL DE PEDIDOS RECIENTES --}}
            @include('cliente.partials._pedidos_recientes')

            {{-- BLOQUE 4: PROMOCIONES Y OFERTAS ACTIVAS --}}
            @include('cliente.partials._promociones')
        </main>
    </div>

    {{-- BLOQUE 5: ASISTENTE VIRTUAL CHATBOT --}}
    @include('cliente.partials.chatbot')

    <script>
        window.addEventListener('pageshow', function (event) {
            if (event.persisted || (window.performance && window.performance.navigation && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });

        const userBtn = document.getElementById('clienteUserBtn');
        const dropdown = document.getElementById('clienteDropdown');
        if (userBtn && dropdown) {
            userBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                dropdown.classList.toggle('show');
            });
            document.addEventListener('click', function () {
                dropdown.classList.remove('show');
            });
        }
    </script>

</body>
</html>
