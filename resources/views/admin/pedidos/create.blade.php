@extends('layouts.admin')

@section('content')

<div class="container">

    <div style="margin-bottom: 25px;">
        <h1>Nuevo pedido</h1>

        <p>
            Registra un nuevo pedido para un cliente.
        </p>
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

    {{-- Errores de validación --}}
    @if ($errors->any())
        <div style="
            padding: 12px;
            margin-bottom: 20px;
            background: #f8d7da;
            color: #842029;
            border-radius: 5px;
        ">

            <strong>Hay errores en el formulario:</strong>

            <ul style="margin-bottom: 0;">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>
    @endif


    {{-- FORMULARIO --}}

    <form
        method="POST"
        action="{{ route('admin.pedidos.store') }}"
    >

        @csrf


        {{-- CLIENTE --}}

        <div style="margin-bottom: 25px;">

            <label
                for="id_cliente"
                style="
                    display: block;
                    margin-bottom: 8px;
                    font-weight: bold;
                "
            >
                Cliente
            </label>

            <select
                name="id_cliente"
                id="id_cliente"
                required
                style="
                    width: 100%;
                    padding: 10px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    background: white;
                "
            >

                <option value="">
                    Seleccione un cliente
                </option>

                @foreach ($clientes as $cliente)

                    <option
                        value="{{ $cliente->id_cliente }}"
                        {{ old('id_cliente') == $cliente->id_cliente ? 'selected' : '' }}
                    >

                        {{ $cliente->usuario->nombre ?? 'Cliente #' . $cliente->id_cliente }}

                    </option>

                @endforeach

            </select>


            {{-- Si no existen clientes --}}

            @if ($clientes->isEmpty())

                <div style="
                    margin-top: 10px;
                    padding: 12px;
                    background: #fff3cd;
                    color: #664d03;
                    border-radius: 5px;
                ">

                    No hay clientes registrados.

                </div>

            @endif

        </div>


        {{-- PLATOS --}}

        <div style="margin-bottom: 25px;">

            <label
                style="
                    display: block;
                    margin-bottom: 10px;
                    font-weight: bold;
                "
            >
                Platos
            </label>


            @forelse ($platos as $plato)

                <div
                    style="
                        display: flex;
                        align-items: center;
                        gap: 15px;
                        margin-bottom: 10px;
                        padding: 15px;
                        border: 1px solid #ddd;
                        border-radius: 5px;
                        background: white;
                    "
                >

                    {{-- Nombre del plato --}}

                    <div style="flex: 1;">

                        <strong>
                            {{ $plato->nombre }}
                        </strong>

                        @if(isset($plato->precio))

                            <div style="
                                margin-top: 5px;
                                color: #666;
                                font-size: 14px;
                            ">

                                Precio:
                                ${{ number_format($plato->precio, 0, ',', '.') }}

                            </div>

                        @endif

                    </div>


                    {{-- Cantidad --}}

                    <div>

                        <label
                            for="cantidad_{{ $plato->id_plato }}"
                            style="
                                margin-right: 8px;
                            "
                        >
                            Cantidad
                        </label>

                        <input
                            type="number"
                            name="items[{{ $plato->id_plato }}][cantidad]"
                            id="cantidad_{{ $plato->id_plato }}"
                            value="{{ old('items.' . $plato->id_plato . '.cantidad', 0) }}"
                            min="0"
                            step="1"
                            style="
                                width: 80px;
                                padding: 8px;
                                border: 1px solid #ccc;
                                border-radius: 5px;
                            "
                        >

                    </div>


                    {{-- ID del plato --}}

                    <input
                        type="hidden"
                        name="items[{{ $plato->id_plato }}][id_plato]"
                        value="{{ $plato->id_plato }}"
                    >

                </div>

            @empty

                <div style="
                    padding: 15px;
                    background: #fff3cd;
                    color: #664d03;
                    border-radius: 5px;
                ">

                    No hay platos disponibles para agregar al pedido.

                </div>

            @endforelse

        </div>


        {{-- BOTONES --}}

        <div style="
            display: flex;
            gap: 10px;
            margin-top: 25px;
        ">

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
                Registrar pedido
            </button>


            <a
                href="{{ route('admin.pedidos.index') }}"
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

@endsection