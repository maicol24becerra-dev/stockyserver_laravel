
@extends('layouts.admin')

@section('content')

<div class="container">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">

        <div>
            <h1>Registrar pago</h1>
            <p>Registra el pago correspondiente al pedido #{{ $pedido->id_pedido }}.</p>
        </div>

        <a
            href="{{ route('admin.pedidos.show', $pedido) }}"
            style="
                padding: 10px 16px;
                background: #6c757d;
                color: white;
                text-decoration: none;
                border-radius: 5px;
            "
        >
            Volver al pedido
        </a>

    </div>


    @if ($errors->any())

        <div
            style="
                padding: 12px;
                margin-bottom: 20px;
                background: #f8d7da;
                color: #842029;
                border-radius: 5px;
            "
        >

            <strong>Hay errores en el formulario:</strong>

            <ul style="margin-bottom: 0;">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Información del pedido --}}
    <div
        style="
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        "
    >

        <h2 style="margin-top: 0;">
            Pedido #{{ $pedido->id_pedido }}
        </h2>

        @php
            $total = $pedido->items->sum(function ($item) {
                return $item->cantidad * $item->precio_unitario;
            });
        @endphp

        <div style="margin-bottom: 10px;">
            <strong>Cliente:</strong>
            {{ $pedido->cliente->usuario->nombre ?? 'No disponible' }}
        </div>

        <div style="margin-bottom: 10px;">
            <strong>Estado:</strong>
            {{ $pedido->estado }}
        </div>

        <div>
            <strong>Total del pedido:</strong>

            <span style="font-size: 20px; font-weight: bold;">
                ${{ number_format($total, 0, ',', '.') }}
            </span>
        </div>

    </div>


    {{-- Formulario --}}
    <div
        style="
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
        "
    >

        <h2 style="margin-top: 0;">
            Datos del pago
        </h2>

        <form
            method="POST"
            action="{{ route('admin.pedidos.pago.store', $pedido) }}"
        >

            @csrf


            <div style="margin-bottom: 20px;">

                <label
                    for="monto_pagado"
                    style="display: block; margin-bottom: 6px;"
                >
                    Monto pagado
                </label>

                <input
                    type="number"
                    name="monto_pagado"
                    id="monto_pagado"
                    value="{{ old('monto_pagado', $total) }}"
                    min="0"
                    step="0.01"
                    required
                    style="
                        width: 100%;
                        padding: 10px;
                        border: 1px solid #ccc;
                        border-radius: 5px;
                    "
                >

            </div>


            <div style="margin-bottom: 20px;">

                <label
                    for="metodo_pago"
                    style="display: block; margin-bottom: 6px;"
                >
                    Método de pago
                </label>

                <select
                    name="metodo_pago"
                    id="metodo_pago"
                    required
                    style="
                        width: 100%;
                        padding: 10px;
                        border: 1px solid #ccc;
                        border-radius: 5px;
                    "
                >

                    <option value="">
                        Seleccione un método
                    </option>

                    <option
                        value="efectivo"
                        {{ old('metodo_pago') === 'efectivo' ? 'selected' : '' }}
                    >
                        Efectivo
                    </option>

                    <option
                        value="tarjeta"
                        {{ old('metodo_pago') === 'tarjeta' ? 'selected' : '' }}
                    >
                        Tarjeta
                    </option>

                    <option
                        value="transferencia"
                        {{ old('metodo_pago') === 'transferencia' ? 'selected' : '' }}
                    >
                        Transferencia
                    </option>

                </select>

            </div>


            <div style="margin-bottom: 25px;">

                <label
                    for="fecha_pago"
                    style="display: block; margin-bottom: 6px;"
                >
                    Fecha del pago
                </label>

                <input
                    type="datetime-local"
                    name="fecha_pago"
                    id="fecha_pago"
                    value="{{ old('fecha_pago', now()->format('Y-m-d\TH:i')) }}"
                    required
                    style="
                        width: 100%;
                        padding: 10px;
                        border: 1px solid #ccc;
                        border-radius: 5px;
                    "
                >

            </div>


            <div style="display: flex; gap: 10px;">

                <button
                    type="submit"
                    style="
                        padding: 10px 18px;
                        background: #198754;
                        color: white;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                    "
                >
                    Registrar pago
                </button>

                <a
                    href="{{ route('admin.pedidos.show', $pedido) }}"
                    style="
                        padding: 10px 18px;
                        background: #6c757d;
                        color: white;
                        text-decoration: none;
                        border-radius: 5px;
                    "
                >
                    Cancelar
                </a>

            </div>

        </form>

    </div>

</div>

@endsection
