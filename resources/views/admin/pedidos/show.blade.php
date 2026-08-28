@extends('layouts.admin')

@section('content')

<div class="container">

```
@php
    $rol = auth()->user()->role?->nombre;

    $estado = strtolower(trim($pedido->estado));

    $total = $pedido->items->sum(function ($item) {
        return $item->cantidad * $item->precio_unitario;
    });
@endphp

{{-- Encabezado --}}

<div style="
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
">

    <div>
        <h1>
            Detalle del pedido #{{ $pedido->id_pedido }}
        </h1>

        <p>
            Información completa del pedido registrado.
        </p>
    </div>

    <a
        href="{{ route('admin.pedidos.index') }}"
        style="
            padding: 10px 16px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        "
    >
        Volver a pedidos
    </a>

</div>


{{-- Mensaje de éxito --}}

@if(session('success'))

    <div style="
        padding: 12px;
        margin-bottom: 20px;
        background: #d1e7dd;
        color: #0f5132;
        border-radius: 5px;
    ">
        {{ session('success') }}
    </div>

@endif


{{-- Mensaje de error --}}

@if(session('error'))

    <div style="
        padding: 12px;
        margin-bottom: 20px;
        background: #f8d7da;
        color: #842029;
        border-radius: 5px;
    ">
        {{ session('error') }}
    </div>

@endif


{{-- Información del pedido --}}

<div style="
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 25px;
">

    <h2 style="margin-top: 0;">
        Información del pedido
    </h2>

    <p>
        <strong>ID:</strong>
        {{ $pedido->id_pedido }}
    </p>

    <p>
        <strong>Fecha:</strong>
        {{ $pedido->fecha?->format('Y-m-d H:i:s') }}
    </p>

    <p>
        <strong>Estado:</strong>

        <strong>
            @switch($estado)

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
    </p>

    <p>
        <strong>Usuario:</strong>

        {{ $pedido->usuario?->nombre
            ?? 'Usuario #' . $pedido->id_usuario
        }}
    </p>

    <p>
        <strong>Cliente:</strong>

        {{ $pedido->cliente?->usuario?->nombre
            ?? 'Cliente #' . $pedido->id_cliente
        }}
    </p>

    <p>
        <strong>ID Cliente:</strong>
        {{ $pedido->id_cliente }}
    </p>

</div>


{{-- Control de estado --}}

<div style="
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 25px;
">

    <h2 style="margin-top: 0;">
        Estado del pedido
    </h2>


    {{-- MESERO --}}

    @if($rol === 'Mesero')

        @if($estado === 'pendiente')

            <p>
                Este pedido está pendiente de ser enviado a cocina.
            </p>

            <form
                method="POST"
                action="{{ route(
                    'admin.pedidos.estado',
                    $pedido
                ) }}"
            >

                @csrf
                @method('PUT')

                <input
                    type="hidden"
                    name="estado"
                    value="en preparación"
                >

                <button
                    type="submit"
                    style="
                        padding: 10px 16px;
                        background: #0d6efd;
                        color: white;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                    "
                >
                    Enviar a cocina
                </button>

            </form>


        @elseif($estado === 'listo')

            <p>
                El pedido está listo para ser entregado.
            </p>

            <form
                method="POST"
                action="{{ route(
                    'admin.pedidos.estado',
                    $pedido
                ) }}"
            >

                @csrf
                @method('PUT')

                <input
                    type="hidden"
                    name="estado"
                    value="entregado"
                >

                <button
                    type="submit"
                    style="
                        padding: 10px 16px;
                        background: #198754;
                        color: white;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                    "
                >
                    Entregar pedido
                </button>

            </form>


        @elseif($estado === 'entregado')

            <p style="color: #198754;">
                ✓ El pedido ya fue entregado.
            </p>

        @else

            <p>
                El pedido está en preparación. El cocinero debe marcarlo como listo.
            </p>

        @endif


    {{-- COCINERO --}}

    @elseif($rol === 'Cocinero')

        @if($estado === 'en preparación')

            <p>
                Este pedido está en preparación.
            </p>

            <form
                method="POST"
                action="{{ route(
                    'admin.pedidos.estado',
                    $pedido
                ) }}"
            >

                @csrf
                @method('PUT')

                <input
                    type="hidden"
                    name="estado"
                    value="listo"
                >

                <button
                    type="submit"
                    style="
                        padding: 10px 16px;
                        background: #198754;
                        color: white;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                    "
                >
                    Marcar como listo
                </button>

            </form>

        @elseif($estado === 'listo')

            <p style="color: #198754;">
                ✓ El pedido ya está listo. Esperando al mesero.
            </p>

        @elseif($estado === 'entregado')

            <p style="color: #198754;">
                ✓ El pedido ya fue entregado.
            </p>

        @else

            <p>
                Este pedido todavía no ha sido enviado a cocina.
            </p>

        @endif


    {{-- ADMINISTRADOR --}}

    @elseif($rol === 'Administrador')

        <p>
            El administrador puede avanzar el pedido siguiendo el flujo:
            <strong>Pendiente → En preparación → Listo → Entregado</strong>.
        </p>

        @if($estado === 'pendiente')

            <form
                method="POST"
                action="{{ route(
                    'admin.pedidos.estado',
                    $pedido
                ) }}"
            >

                @csrf
                @method('PUT')

                <input
                    type="hidden"
                    name="estado"
                    value="en preparación"
                >

                <button
                    type="submit"
                    style="
                        padding: 10px 16px;
                        background: #0d6efd;
                        color: white;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                    "
                >
                    Enviar a cocina
                </button>

            </form>


        @elseif($estado === 'en preparación')

            <form
                method="POST"
                action="{{ route(
                    'admin.pedidos.estado',
                    $pedido
                ) }}"
            >

                @csrf
                @method('PUT')

                <input
                    type="hidden"
                    name="estado"
                    value="listo"
                >

                <button
                    type="submit"
                    style="
                        padding: 10px 16px;
                        background: #198754;
                        color: white;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                    "
                >
                    Marcar como listo
                </button>

            </form>


        @elseif($estado === 'listo')

            <form
                method="POST"
                action="{{ route(
                    'admin.pedidos.estado',
                    $pedido
                ) }}"
            >

                @csrf
                @method('PUT')

                <input
                    type="hidden"
                    name="estado"
                    value="entregado"
                >

                <button
                    type="submit"
                    style="
                        padding: 10px 16px;
                        background: #198754;
                        color: white;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                    "
                >
                    Entregar pedido
                </button>

            </form>


        @elseif($estado === 'entregado')

            <p style="color: #198754;">
                ✓ El pedido ya fue entregado.
            </p>

        @endif

    @endif

</div>


{{-- Platos --}}

<div style="
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 25px;
">

    <h2 style="margin-top: 0;">
        Platos del pedido
    </h2>

    <div style="overflow-x: auto;">

        <table style="
            width: 100%;
            border-collapse: collapse;
        ">

            <thead>

                <tr>

                    <th style="padding: 10px; text-align: left;">
                        Plato
                    </th>

                    <th style="padding: 10px; text-align: left;">
                        Cantidad
                    </th>

                    <th style="padding: 10px; text-align: left;">
                        Precio unitario
                    </th>

                    <th style="padding: 10px; text-align: left;">
                        Subtotal
                    </th>

                </tr>

            </thead>

            <tbody>

                @foreach($pedido->items as $item)

                    @php
                        $subtotal =
                            $item->cantidad *
                            $item->precio_unitario;
                    @endphp

                    <tr>

                        <td style="padding: 10px;">
                            {{ $item->plato?->nombre ?? 'Plato' }}
                        </td>

                        <td style="padding: 10px;">
                            {{ $item->cantidad }}
                        </td>

                        <td style="padding: 10px;">
                            ${{ number_format(
                                $item->precio_unitario,
                                0,
                                ',',
                                '.'
                            ) }}
                        </td>

                        <td style="padding: 10px;">
                            ${{ number_format(
                                $subtotal,
                                0,
                                ',',
                                '.'
                            ) }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

    <div style="
        text-align: right;
        margin-top: 20px;
        font-size: 20px;
        font-weight: bold;
    ">

        Total del pedido:

        ${{ number_format(
            $total,
            0,
            ',',
            '.'
        ) }}

    </div>

</div>


{{-- Pago --}}

<div style="
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 25px;
">

    <h2 style="margin-top: 0;">
        Pago
    </h2>

    @if($pedido->pago)

        <p>
            <strong>Estado:</strong>

            <span style="color: #198754;">
                ✓ Pagado
            </span>
        </p>

        <p>
            <strong>ID Pago:</strong>
            {{ $pedido->pago->id_pago }}
        </p>

        <p>
            <strong>Monto pagado:</strong>

            ${{ number_format(
                $pedido->pago->monto_pagado,
                0,
                ',',
                '.'
            ) }}
        </p>

        <p>
            <strong>Método de pago:</strong>
            {{ ucfirst($pedido->pago->metodo_pago) }}
        </p>

        <p>
            <strong>Fecha del pago:</strong>
            {{ $pedido->pago->fecha_pago }}
        </p>

    @else

        <p>
            <strong>Estado:</strong>

            <span style="color: #dc3545;">
                Pendiente
            </span>
        </p>

        {{-- El pago solamente se registra cuando el pedido está entregado --}}

        @if(
            $estado === 'entregado'
            &&
            (
                $rol === 'Administrador'
                || $rol === 'Mesero'
            )
        )

            <a
                href="{{ route(
                    'admin.pedidos.pago.create',
                    $pedido
                ) }}"
                style="
                    display: inline-block;
                    padding: 10px 16px;
                    background: #ffc107;
                    color: #212529;
                    text-decoration: none;
                    border-radius: 5px;
                "
            >
                Registrar pago
            </a>

        @elseif($estado !== 'entregado')

            <p>
                El pago podrá registrarse cuando el pedido sea entregado.
            </p>

        @endif

    @endif

</div>


{{-- Volver --}}

<a
    href="{{ route('admin.pedidos.index') }}"
    style="
        display: inline-block;
        padding: 10px 16px;
        background: #6c757d;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    "
>
    Volver a pedidos
</a>
```

</div>

@endsection
