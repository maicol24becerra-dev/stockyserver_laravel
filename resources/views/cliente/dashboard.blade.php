@extends('layouts.admin')

@section('content')

<div class="container">
    {{-- Barra de acciones del cliente --}}
<div style="
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
">

    <form
        method="POST"
        action="{{ route('logout') }}"
        style="margin: 0;"
    >
        @csrf

        <button
            type="submit"
            style="
                padding: 10px 16px;
                background: #dc3545;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 14px;
            "
        >
            Cerrar sesión
        </button>

    </form>

</div>

    {{-- Encabezado --}}

    <div style="
        background: white;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 25px;
        margin-bottom: 25px;
    ">

        <h1 style="margin-top: 0;">
            Bienvenido, {{ $usuario->nombre }}
        </h1>

        <p style="margin-bottom: 0;">
            Consulta tus pedidos y revisa su estado.
        </p>

    </div>


    {{-- Estadísticas --}}

    <div style="
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    ">

        {{-- Total pedidos --}}

        <div style="
            background: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
        ">

            <h3 style="margin-top: 0;">
                Mis pedidos
            </h3>

            <strong style="font-size: 28px;">
                {{ $totalPedidos }}
            </strong>

        </div>


        {{-- Pendientes --}}

        <div style="
            background: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
        ">

            <h3 style="margin-top: 0;">
                Pendientes
            </h3>

            <strong style="font-size: 28px;">
                {{ $pedidosPendientes }}
            </strong>

        </div>


        {{-- En preparación --}}

        <div style="
            background: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
        ">

            <h3 style="margin-top: 0;">
                En preparación
            </h3>

            <strong style="font-size: 28px;">
                {{ $pedidosEnPreparacion }}
            </strong>

        </div>


        {{-- Listos --}}

        <div style="
            background: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
        ">

            <h3 style="margin-top: 0;">
                Listos
            </h3>

            <strong style="font-size: 28px;">
                {{ $pedidosListos }}
            </strong>

        </div>


        {{-- Entregados --}}

        <div style="
            background: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
        ">

            <h3 style="margin-top: 0;">
                Entregados
            </h3>

            <strong style="font-size: 28px;">
                {{ $pedidosEntregados }}
            </strong>

        </div>

    </div>


    {{-- Acceso al menú --}}

    <div style="
        background: white;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 25px;
    ">

        <h2 style="margin-top: 0;">
            Menú
        </h2>

        <p>
            Consulta los platos disponibles del establecimiento.
        </p>

        <a
            href="{{ route('admin.platos.index') }}"
            style="
                display: inline-block;
                padding: 10px 16px;
                background: #198754;
                color: white;
                text-decoration: none;
                border-radius: 5px;
            "
        >
            Ver menú
        </a>

    </div>


    {{-- Mis pedidos --}}

    <div style="
        background: white;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 20px;
    ">

        <h2 style="margin-top: 0;">
            Mis pedidos
        </h2>


        @if($pedidos->isEmpty())

            <div style="
                padding: 25px;
                text-align: center;
                border: 1px solid #eee;
                border-radius: 8px;
            ">

                <h3>
                    No tienes pedidos registrados.
                </h3>

                <p>
                    Cuando realices un pedido aparecerá aquí.
                </p>

            </div>

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

                            <th style="padding: 10px;">
                                Pedido
                            </th>

                            <th style="padding: 10px;">
                                Fecha
                            </th>

                            <th style="padding: 10px;">
                                Estado
                            </th>

                            <th style="padding: 10px;">
                                Total
                            </th>

                            <th style="padding: 10px;">
                                Pago
                            </th>

                            <th style="padding: 10px;">
                                Acción
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($pedidos as $pedido)

                            @php

                                $total = $pedido->items->sum(
                                    function ($item) {
                                        return
                                            $item->cantidad
                                            * $item->precio_unitario;
                                    }
                                );

                                $estado = strtolower(
                                    trim($pedido->estado)
                                );

                            @endphp


                            <tr style="
                                border-bottom: 1px solid #eee;
                            ">

                                {{-- ID --}}

                                <td style="padding: 10px;">
                                    #{{ $pedido->id_pedido }}
                                </td>


                                {{-- Fecha --}}

                                <td style="padding: 10px;">
                                    {{ $pedido->fecha?->format('d/m/Y H:i') }}
                                </td>


                                {{-- Estado --}}

                                <td style="padding: 10px;">

                                    <strong>

                                        @switch($estado)

                                            @case('pendiente')
                                                Pendiente
                                                @break

                                            @case('en preparación')
                                                En preparación
                                                @break

                                            @case('listo')
                                                ¡Listo!
                                                @break

                                            @case('entregado')
                                                Entregado
                                                @break

                                            @default
                                                {{ $pedido->estado }}

                                        @endswitch

                                    </strong>

                                </td>


                                {{-- Total --}}

                                <td style="padding: 10px;">

                                    ${{ number_format(
                                        $total,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </td>


                                {{-- Pago --}}

                                <td style="padding: 10px;">

                                    @if($pedido->pago)

                                        <strong>
                                            ✓ Pagado
                                        </strong>

                                    @else

                                        <strong>
                                            Pendiente
                                        </strong>

                                    @endif

                                </td>


                                {{-- Acción --}}

                                <td style="padding: 10px;">

                                    <a
                                        href="{{ route(
                                            'admin.pedidos.show',
                                            $pedido
                                        ) }}"
                                    >
                                        Ver pedido
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
