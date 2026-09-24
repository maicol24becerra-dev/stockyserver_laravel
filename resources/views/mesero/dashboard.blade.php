@php
    $section = request('seccion', 'activos');
    $activeOrders = $pedidos->filter(fn ($pedido) => !in_array(strtolower(trim($pedido->estado)), ['entregado', 'cancelado']));
    $todayOrders = $pedidos->filter(fn ($pedido) => $pedido->fecha?->isToday());
    $pendingOrders = $activeOrders->filter(fn ($pedido) => strtolower(trim($pedido->estado)) === 'pendiente');
    $completedOrders = $todayOrders->filter(fn ($pedido) => strtolower(trim($pedido->estado)) === 'entregado');
    $categories = $platos->pluck('categoria')->filter()->unique()->values();
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ucfirst($section === 'crear' ? 'Crear pedido' : ($section === 'historial' ? 'Historial' : 'Pedidos activos')) }} - El Cielo</title>
    <link rel="icon" href="{{ asset('images/logo-circle.png') }}?v={{ time() }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mesero/mesero-dashboard.css') }}?v={{ time() }}">
</head>
<body>

<!-- Session Destroyer Script -->
<script src="{{ asset('js/session-destroyer.js') }}"></script>

    {{-- BLOQUE 1: SIDEBAR DE NAVEGACIÓN --}}
    @include('mesero.partials._sidebar')

    {{-- WORKSPACE PRINCIPAL --}}
    <main class="workspace">
        {{-- BLOQUE 2: BARRA SUPERIOR Y ALERTAS --}}
        @include('mesero.partials._topbar')

        {{-- BLOQUES CONDICIONALES DE CONTENIDO --}}
        @if($section === 'activos')
            {{-- BLOQUE 3: PEDIDOS ACTIVOS Y MODALES DE ATENCIÓN --}}
            @include('mesero.partials._pedidos_activos')

        @elseif($section === 'crear')
            {{-- BLOQUE 4: CATÁLOGO Y TOMA DE PEDIDO --}}
            @include('mesero.partials._crear_pedido')

        @else
            {{-- BLOQUE 5: HISTORIAL DE PEDIDOS FINALIZADOS --}}
            @include('mesero.partials._historial')
        @endif
    </main>

    {{-- BLOQUE 6: BOTÓN FLOTANTE Y SCRIPTS INTERACTIVOS --}}
    @include('mesero.partials._scripts')

</body>
</html>
