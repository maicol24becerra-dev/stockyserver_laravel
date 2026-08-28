<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gestión de Usuarios - StockYServe</title>
</head>

<body>

    <h1>StockYServe</h1>

    <h2>Gestión de Usuarios</h2>

    @if (session('success'))
        <p>
            <strong>{{ session('success') }}</strong>
        </p>
    @endif

    @if (session('error'))
        <p>
            <strong>{{ session('error') }}</strong>
        </p>
    @endif

    <p>
        <a href="{{ route('admin.dashboard') }}">
            ← Volver al panel
        </a>
    </p>

    <p>
        <a href="{{ route('admin.usuarios.create') }}">
            + Nuevo usuario
        </a>
    </p>

    @if ($usuarios->count() > 0)

        <table border="1" cellpadding="8" cellspacing="0">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($usuarios as $usuario)

                    <tr>

                        <td>
                            {{ $usuario->id_usuario }}
                        </td>

                        <td>
                            {{ $usuario->nombre }}
                        </td>

                        <td>
                            {{ $usuario->correo }}
                        </td>

                        <td>
                            {{ $usuario->telefono ?? '-' }}
                        </td>

                        <td>
                            {{ $usuario->role->nombre ?? 'Sin rol' }}
                        </td>

                        <td>
                            {{ $usuario->estado }}
                        </td>

                        <td>

                            <a href="{{ route('admin.usuarios.show', $usuario) }}">
                                Ver
                            </a>

                            |

                            <a href="{{ route('admin.usuarios.edit', $usuario) }}">
                                Editar
                            </a>

                            |

                            @if (auth()->id() !== $usuario->id_usuario)

                                <form
                                    action="{{ route('admin.usuarios.destroy', $usuario) }}"
                                    method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?');"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit">
                                        Eliminar
                                    </button>

                                </form>

                            @else

                                <span>
                                    Usuario actual
                                </span>

                            @endif

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

        <br>

        {{ $usuarios->links() }}

    @else

        <p>No hay usuarios registrados.</p>

    @endif

</body>
</html>