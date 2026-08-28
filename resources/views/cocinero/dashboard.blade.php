<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Cocina — El Cielo</title>
    <link rel="stylesheet" href="{{ asset('css/cocinero-dashboard.css') }}">
</head>
<body>

<header class="header">
    <div class="brand">
        <h1>El Cielo</h1>
        <p>Centro Vacacional y Recreacional</p>
    </div>
    <div class="user">
        🍳 {{ auth()->user()->nombre }}
    </div>
</header>

<main class="container">

    <div class="top">
        <div><h2>Panel de Cocina</h2></div>
        <div class="refresh">Actualizando cada 15s</div>
    </div>

    {{-- Estadísticas --}}
    <section class="cards">
        <div class="card">
            <span>Pendientes</span>
            <strong>{{ $pendientes }}</strong>
        </div>
        <div class="card">
            <span>En preparación</span>
            <strong>{{ $enPreparacion }}</strong>
        </div>
        <div class="card">
            <span>Urgentes</span>
            <strong>{{ $urgentes }}</strong>
        </div>
        <div class="card">
            <span>Con hora entrega</span>
            <strong>{{ $conHoraEntrega }}</strong>
        </div>
    </section>

    {{-- Pedidos activos --}}
    <section class="orders">

        <div class="orders-header">
            <h3>Pedidos Activos</h3>
        </div>

        <div class="filters">
            <a href="{{ route('cocinero.dashboard') }}"
               class="filter {{ !request('estado') ? 'active' : '' }}">
                Todos
            </a>
            <a href="{{ route('cocinero.dashboard', ['estado' => 'pendiente']) }}"
               class="filter {{ request('estado') === 'pendiente' ? 'active' : '' }}">
                Pendientes
            </a>
            <a href="{{ route('cocinero.dashboard', ['estado' => 'en preparación']) }}"
               class="filter {{ request('estado') === 'en preparación' ? 'active' : '' }}">
                En preparación
            </a>
            <a href="{{ route('cocinero.dashboard', ['estado' => 'urgente']) }}"
               class="filter {{ request('estado') === 'urgente' ? 'active' : '' }}">
                Urgentes
            </a>
        </div>

        @if($pedidos->count())
            <div style="padding: 20px;">
                @foreach($pedidos as $pedido)
                    <div class="order-item">
                        <strong>Pedido #{{ $pedido->id_pedido }}</strong>
                        <p>Cliente: {{ $pedido->cliente?->usuario?->nombre ?? 'Sin cliente' }}</p>
                        <p>Estado: <strong>{{ ucfirst($pedido->estado) }}</strong></p>
                        <a href="{{ route('admin.pedidos.show', $pedido) }}">Ver detalle</a>

                        @if($pedido->estado === 'en preparación')
                            <form method="POST"
                                  action="{{ route('admin.pedidos.estado', $pedido) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="estado" value="listo">
                                <button type="submit" class="btn-listo">
                                    ✓ Marcar como listo
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty">
                <h3>No hay pedidos pendientes en este momento.</h3>
                <p>La pantalla se actualiza automáticamente.</p>
            </div>
        @endif

    </section>

    <div class="logout">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Cerrar sesión</button>
        </form>
    </div>

</main>

<script>
    setTimeout(function () { window.location.reload(); }, 15000);
</script>

</body>
</html>
