@extends('layouts.admin')

@section('content')

<div class="container">


{{-- ========================================================= --}}
{{-- ENCABEZADO --}}
{{-- ========================================================= --}}

<div style="
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    gap: 20px;
    flex-wrap: wrap;
">

    <div>
        <h1 style="margin-bottom: 5px;">
            Dashboard
        </h1>

        <p style="margin: 0; color: #666;">
            Resumen general de StockYServe.
        </p>
    </div>

    {{-- Usuario y cerrar sesión --}}
    <div style="
        display: flex;
        align-items: center;
        gap: 15px;
    ">

        <span style="
            color: #555;
            font-weight: 500;
        ">
            {{ auth()->user()->nombre }}
        </span>

        <form
            action="{{ route('logout') }}"
            method="POST"
            style="margin: 0;"
        >
            @csrf

            <button
                type="submit"
                style="
                    border: none;
                    padding: 10px 16px;
                    background: #dc3545;
                    color: white;
                    border-radius: 6px;
                    cursor: pointer;
                    font-weight: 500;
                "
            >
                Cerrar sesión
            </button>
        </form>

    </div>

</div>


{{-- ========================================================= --}}
{{-- MENSAJE DE ÉXITO --}}
{{-- ========================================================= --}}

@if(session('success'))

    <div style="
        background: #d1e7dd;
        color: #0f5132;
        border: 1px solid #badbcc;
        border-radius: 6px;
        padding: 12px 15px;
        margin-bottom: 20px;
    ">
        {{ session('success') }}
    </div>

@endif


{{-- ========================================================= --}}
{{-- MENSAJES DE ERROR --}}
{{-- ========================================================= --}}

@if($errors->any())

    <div style="
        background: #f8d7da;
        color: #842029;
        border: 1px solid #f5c2c7;
        border-radius: 6px;
        padding: 12px 15px;
        margin-bottom: 20px;
    ">

        <ul style="margin: 0; padding-left: 20px;">

            @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif


{{-- ========================================================= --}}
{{-- TARJETAS PRINCIPALES --}}
{{-- ========================================================= --}}

<div style="
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
">

    {{-- Total pedidos --}}
    <div style="
        background: white;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 20px;
    ">
        <p style="margin: 0 0 8px 0; color: #666;">
            Total de pedidos
        </p>

        <h2 style="margin: 0; font-size: 32px;">
            {{ $totalPedidos }}
        </h2>
    </div>


    {{-- Pendientes --}}
    <div style="
        background: white;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 20px;
    ">
        <p style="margin: 0 0 8px 0; color: #666;">
            Pendientes
        </p>

        <h2 style="margin: 0; font-size: 32px;">
            {{ $pedidosPendientes }}
        </h2>
    </div>


    {{-- En preparación --}}
    <div style="
        background: white;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 20px;
    ">
        <p style="margin: 0 0 8px 0; color: #666;">
            En preparación
        </p>

        <h2 style="margin: 0; font-size: 32px;">
            {{ $pedidosPreparacion }}
        </h2>
    </div>


    {{-- Listos --}}
    <div style="
        background: white;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 20px;
    ">
        <p style="margin: 0 0 8px 0; color: #666;">
            Listos
        </p>

        <h2 style="margin: 0; font-size: 32px;">
            {{ $pedidosListos }}
        </h2>
    </div>


    {{-- Entregados --}}
    <div style="
        background: white;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 20px;
    ">
        <p style="margin: 0 0 8px 0; color: #666;">
            Entregados
        </p>

        <h2 style="margin: 0; font-size: 32px;">
            {{ $pedidosEntregados }}
        </h2>
    </div>


    {{-- Pagos pendientes --}}
    <div style="
        background: white;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 20px;
    ">
        <p style="margin: 0 0 8px 0; color: #666;">
            Pagos pendientes
        </p>

        <h2 style="margin: 0; font-size: 32px;">
            {{ $pagosPendientes }}
        </h2>
    </div>

</div>


{{-- ========================================================= --}}
{{-- INFORMACIÓN FINANCIERA --}}
{{-- ========================================================= --}}

<div style="
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
">

    {{-- Total pagado --}}
    <div style="
        background: white;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 25px;
    ">
        <p style="margin: 0 0 10px 0; color: #666;">
            Total recaudado
        </p>

        <h2 style="margin: 0; font-size: 30px;">
            ${{ number_format($totalPagado, 0, ',', '.') }}
        </h2>
    </div>


    {{-- Platos disponibles --}}
    <div style="
        background: white;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 25px;
    ">
        <p style="margin: 0 0 10px 0; color: #666;">
            Platos disponibles
        </p>

        <h2 style="margin: 0; font-size: 30px;">
            {{ $platosDisponibles }}
        </h2>
    </div>

</div>


{{-- ========================================================= --}}
{{-- ACCESOS RÁPIDOS --}}
{{-- ========================================================= --}}

<div style="
    background: white;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 25px;
    margin-bottom: 30px;
">

    <h2 style="margin-top: 0;">
        Accesos rápidos
    </h2>

    <div style="
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    ">

        <a
            href="{{ route('admin.pedidos.index') }}"
            style="
                display: inline-block;
                padding: 10px 16px;
                background: #0d6efd;
                color: white;
                text-decoration: none;
                border-radius: 5px;
            "
        >
            Gestionar pedidos
        </a>


        <a
            href="{{ route('admin.pedidos.create') }}"
            style="
                display: inline-block;
                padding: 10px 16px;
                background: #198754;
                color: white;
                text-decoration: none;
                border-radius: 5px;
            "
        >
            Nuevo pedido
        </a>


        @if(Route::has('admin.usuarios.index'))

            <a
                href="{{ route('admin.usuarios.index') }}"
                style="
                    display: inline-block;
                    padding: 10px 16px;
                    background: #6f42c1;
                    color: white;
                    text-decoration: none;
                    border-radius: 5px;
                "
            >
                Gestionar usuarios
            </a>

        @endif


        @if(Route::has('admin.platos.index'))

            <a
                href="{{ route('admin.platos.index') }}"
                style="
                    display: inline-block;
                    padding: 10px 16px;
                    background: #fd7e14;
                    color: white;
                    text-decoration: none;
                    border-radius: 5px;
                "
            >
                Gestionar platos
            </a>

        @endif

    </div>

</div>


{{-- ========================================================= --}}
{{-- ÚLTIMOS PEDIDOS --}}
{{-- ========================================================= --}}

<div style="
    background: white;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 25px;
    margin-bottom: 30px;
">

    <div style="
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    ">

        <h2 style="margin: 0;">
            Últimos pedidos
        </h2>

        <a
            href="{{ route('admin.pedidos.index') }}"
            style="text-decoration: none;"
        >
            Ver todos
        </a>

    </div>


    @if($ultimosPedidos->isEmpty())

        <p>
            No hay pedidos registrados.
        </p>

    @else

        <div style="overflow-x: auto;">

            <table style="
                width: 100%;
                border-collapse: collapse;
            ">

                <thead>

                    <tr style="
                        border-bottom: 2px solid #ddd;
                        text-align: left;
                    ">

                        <th style="padding: 10px;">ID</th>
                        <th style="padding: 10px;">Fecha</th>
                        <th style="padding: 10px;">Cliente</th>
                        <th style="padding: 10px;">Usuario</th>
                        <th style="padding: 10px;">Estado</th>
                        <th style="padding: 10px;">Pago</th>
                        <th style="padding: 10px;">Acción</th>

                    </tr>

                </thead>


                <tbody>

                    @foreach($ultimosPedidos as $pedido)

                        @php
                            $estadoPedido = strtolower(
                                trim($pedido->estado)
                            );
                        @endphp

                        <tr style="
                            border-bottom: 1px solid #eee;
                        ">

                            <td style="padding: 10px;">
                                #{{ $pedido->id_pedido }}
                            </td>


                            <td style="padding: 10px;">
                                {{ $pedido->fecha?->format('d/m/Y H:i') }}
                            </td>


                            <td style="padding: 10px;">
                                {{ $pedido->cliente?->usuario?->nombre
                                    ?? 'Cliente #' . $pedido->id_cliente }}
                            </td>


                            <td style="padding: 10px;">
                                {{ $pedido->usuario?->nombre
                                    ?? 'Usuario #' . $pedido->id_usuario }}
                            </td>


                            <td style="padding: 10px;">

                                <strong>

                                    @switch($estadoPedido)

                                        @case('pendiente')
                                            Pendiente
                                            @break

                                        @case('en preparación')
                                            En preparación
                                            @break

                                        @case('listo')
                                            Listo
                                            @break

                                        @case('entregado')
                                            Entregado
                                            @break

                                        @default
                                            {{ $pedido->estado }}

                                    @endswitch

                                </strong>

                            </td>


                            <td style="padding: 10px;">

                                @if($pedido->pago)

                                    <strong style="color: #198754;">
                                        ✓ Pagado
                                    </strong>

                                @else

                                    <strong style="color: #dc3545;">
                                        Pendiente
                                    </strong>

                                @endif

                            </td>


                            <td style="padding: 10px;">

                                <a
                                    href="{{ route(
                                        'admin.pedidos.show',
                                        $pedido
                                    ) }}"
                                >
                                    Ver detalle
                                </a>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @endif

</div>


</div>

@endsection
