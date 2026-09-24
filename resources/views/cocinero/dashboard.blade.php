<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cocina — El Cielo</title>
    <link rel="icon" href="{{ asset('images/logo-circle.png') }}?v={{ time() }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cocinero/cocinero-dashboard.css') }}?v={{ time() }}">
</head>
<body>

<!-- Session Destroyer Script -->
<script src="{{ asset('js/session-destroyer.js') }}"></script>

    {{-- BLOQUE 1: SIDEBAR DE COCINA --}}
    @include('cocinero.partials._sidebar')

    {{-- CONTENEDOR PRINCIPAL --}}
    <div class="main-wrapper">

        {{-- BLOQUE 2: BARRA SUPERIOR DE COCINA --}}
        @include('cocinero.partials._topbar')

        {{-- CONTENIDO PRINCIPAL --}}
        <main class="main-content">
            {{-- BLOQUE 3: KPIS Y CONTADORES DE ESTADO --}}
            @include('cocinero.partials._kpis')

            {{-- BLOQUE 4: FILTROS Y TARJETAS DE COMANDAS ACTIVAS --}}
            @include('cocinero.partials._pedidos_list')
        </main>
    </div>

    {{-- SCRIPTS DE RECARGA Y ESTADO --}}
    <script>
        window.addEventListener('pageshow', function (event) {
            if (event.persisted || (window.performance && window.performance.navigation && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });

        setTimeout(function () { window.location.reload(); }, 15000);

        const userBtn = document.getElementById('cocineroUserBtn');
        const dropdown = document.getElementById('cocineroDropdown');
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
