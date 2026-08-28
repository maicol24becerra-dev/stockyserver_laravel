<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platos — El Cielo</title>
    <link rel="stylesheet" href="{{ asset('css/admin-platos.css') }}">
</head>
<body>

<header>
    <h1>El Cielo</h1>
</header>

<main>

    <div class="top-bar">
        <h2>Gestión de Platos</h2>
        <div>
            <a href="{{ route('admin.dashboard') }}">← Volver al panel</a>
            &nbsp;|&nbsp;
            <a href="{{ route('admin.platos.create') }}">+ Nuevo plato</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <div class="card">

        @if($platos->isEmpty())
            <p>No hay platos registrados.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Categoría</th>
                        <th>Disponibilidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($platos as $plato)
                        <tr>
                            <td>{{ $plato->id_plato }}</td>
                            <td>{{ $plato->nombre }}</td>
                            <td class="precio">${{ number_format($plato->precio, 0, ',', '.') }}</td>
                            <td>{{ $plato->categoria ?? 'Sin categoría' }}</td>
                            <td>
                                @if($plato->disponibilidad > 0)
                                    <span class="disponible">Disponible ({{ $plato->disponibilidad }})</span>
                                @else
                                    <span class="no-disponible">No disponible</span>
                                @endif
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('admin.platos.show', $plato) }}">Ver</a>
                                    <a href="{{ route('admin.platos.edit', $plato) }}">Editar</a>

                                    <form method="POST"
                                          action="{{ route('admin.platos.disponibilidad', $plato) }}">
                                        @csrf
                                        <input type="hidden" name="disponibilidad"
                                               value="{{ $plato->disponibilidad > 0 ? 0 : 1 }}">
                                        <button type="submit"
                                                class="{{ $plato->disponibilidad > 0 ? 'btn-secondary' : 'btn-success' }}">
                                            {{ $plato->disponibilidad > 0 ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    </form>

                                    <form method="POST"
                                          action="{{ route('admin.platos.destroy', $plato) }}"
                                          onsubmit="return confirm('¿Seguro que deseas eliminar este plato?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination-wrap">
                {{ $platos->links() }}
            </div>
        @endif

    </div>

</main>

</body>
</html>
