@extends('layouts.admin')

@section('content')

<div class="container">

    <div style="
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    ">

        <div>
            <h1>Gestión de Pedidos</h1>

            <p>
                Consulta y administra los pedidos registrados.
            </p>
        </div>

        @if(
            auth()->user()->role?->nombre === 'Administrador'
            || auth()->user()->role?->nombre === 'Mesero'
        )

            <a
                href="{{ route('admin.pedidos.create') }}"
                style="
                    padding: 10px 16px;
                    background: #198754;
                    color: white;
                    text-decoration: none;
                    border-radius: 5px;
                "
            >
                Nuevo pedido
            </a>

        @endif

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


    @if($pedidos->isEmpty())

        <div style="
            padding: 30px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 8px;
        ">

            <h3>No hay pedidos registrados</h3>

            <p>
                Cuando se registre un pedido aparecerá aquí.
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
                            ID
                        </th>

                        <th style="padding: 10px;">
                            Fecha
                        </th>

                        <th style="padding: 10px;">
                            Cliente
                        </th>

                        <th style="padding: 10px;">
                            Usuario
                        </th>

                        <th style="padding: 10px;">
                            Estado
                        </th>

                        <th style="padding: 10px;">
                            Items
                        </th>

                        <th style="padding: 10px;">
                            Total
                        </th>

                        <th style="padding: 10px;">
                            Pago
                        </th>

                        <th style="padding: 10px;">
                            Acciones
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

                            $rol = auth()->user()->role?->nombre;

                            $estado = strtolower(
                                trim($pedido->estado)
                            );

                        @endphp


                        <tr style="
                            border-bottom: 1px solid #eee;
                        ">

                            {{-- ID --}}

                            <td style="padding: 10px;">
                                {{ $pedido->id_pedido }}
                            </td>


                            {{-- Fecha --}}

                            <td style="padding: 10px;">
                                {{ $pedido->fecha?->format('d/m/Y H:i') }}
                            </td>


                            {{-- Cliente --}}

                            <td style="padding: 10px;">

                                @if($pedido->cliente?->usuario)

                                    {{ $pedido->cliente->usuario->nombre }}

                                @else

                                    Cliente #{{ $pedido->id_cliente }}

                                @endif

                            </td>


                            {{-- Usuario --}}

                            <td style="padding: 10px;">

                                {{ $pedido->usuario?->nombre
                                    ?? 'Usuario #' . $pedido->id_usuario
                                }}

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


                            {{-- Items --}}

                            <td style="padding: 10px;">
                                {{ $pedido->items->sum('cantidad') }}
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

                                    <strong style="color: #198754;">
                                        ✓ Pagado
                                    </strong>

                                @elseif($estado === 'entregado')

                                    <strong style="color: #dc3545;">
                                        Pendiente
                                    </strong>

                                @else

                                    <span style="color: #6c757d;">
                                        Pendiente de entrega
                                    </span>

                                @endif

                            </td>


                            {{-- Acciones --}}

                            <td style="padding: 10px;">

                                <div style="
                                    display: flex;
                                    flex-direction: column;
                                    gap: 8px;
                                ">


                                    {{-- Ver detalle --}}

                                    <a
                                        href="{{ route(
                                            'admin.pedidos.show',
                                            $pedido
                                        ) }}"
                                    >
                                        Ver detalle
                                    </a>


                                    {{-- ======================================
                                         MESERO
                                         ====================================== --}}

                                    @if($rol === 'Mesero')

                                        {{-- Pendiente → En preparación --}}

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
                                                        padding: 7px 10px;
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

                                        @endif


                                        {{-- Listo → Entregado --}}

                                        @if($estado === 'listo')

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
                                                        padding: 7px 10px;
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

                                        @endif


                                        {{-- Registrar pago
                                             SOLO cuando está entregado --}}

                                        @if(
                                            $estado === 'entregado'
                                            && !$pedido->pago
                                        )

                                            <a
                                                href="{{ route(
                                                    'admin.pedidos.pago.create',
                                                    $pedido
                                                ) }}"
                                                style="
                                                    padding: 7px 10px;
                                                    background: #ffc107;
                                                    color: #212529;
                                                    text-decoration: none;
                                                    border-radius: 5px;
                                                    text-align: center;
                                                "
                                            >
                                                Registrar pago
                                            </a>

                                        @endif

                                    @endif


                                    {{-- ======================================
                                         COCINERO
                                         ====================================== --}}

                                    @if($rol === 'Cocinero')

                                        {{-- En preparación → Listo --}}

                                        @if($estado === 'en preparación')

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
                                                        padding: 7px 10px;
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

                                        @endif

                                    @endif


                                    {{-- ======================================
                                         ADMINISTRADOR
                                         ====================================== --}}

                                    @if($rol === 'Administrador')

                                        {{-- Cambiar estado --}}

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.pedidos.estado',
                                                $pedido
                                            ) }}"
                                        >

                                            @csrf
                                            @method('PUT')

                                            <label
                                                for="estado_{{ $pedido->id_pedido }}"
                                            >
                                                Cambiar estado:
                                            </label>

                                            <select
                                                id="estado_{{ $pedido->id_pedido }}"
                                                name="estado"
                                                onchange="this.form.submit()"
                                                style="
                                                    padding: 7px;
                                                    border: 1px solid #ccc;
                                                    border-radius: 5px;
                                                    width: 100%;
                                                "
                                            >

                                                <option
                                                    value="pendiente"
                                                    {{ $estado === 'pendiente'
                                                        ? 'selected'
                                                        : ''
                                                    }}
                                                >
                                                    Pendiente
                                                </option>

                                                <option
                                                    value="en preparación"
                                                    {{ $estado === 'en preparación'
                                                        ? 'selected'
                                                        : ''
                                                    }}
                                                >
                                                    En preparación
                                                </option>

                                                <option
                                                    value="listo"
                                                    {{ $estado === 'listo'
                                                        ? 'selected'
                                                        : ''
                                                    }}
                                                >
                                                    Listo
                                                </option>

                                                <option
                                                    value="entregado"
                                                    {{ $estado === 'entregado'
                                                        ? 'selected'
                                                        : ''
                                                    }}
                                                >
                                                    Entregado
                                                </option>

                                            </select>

                                        </form>


                                        {{-- Registrar pago
                                             SOLO cuando está entregado --}}

                                        @if(
                                            $estado === 'entregado'
                                            && !$pedido->pago
                                        )

                                            <a
                                                href="{{ route(
                                                    'admin.pedidos.pago.create',
                                                    $pedido
                                                ) }}"
                                                style="
                                                    padding: 7px 10px;
                                                    background: #ffc107;
                                                    color: #212529;
                                                    text-decoration: none;
                                                    border-radius: 5px;
                                                    text-align: center;
                                                "
                                            >
                                                Registrar pago
                                            </a>

                                        @endif

                                    @endif


                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @endif

</div>

@endsection