<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ver usuario - StockYServe</title>
</head>

<body>

    <h1>StockYServe</h1>

    <h2>Información del usuario</h2>

    <p>
        <a href="{{ route('admin.usuarios.index') }}">
            ← Volver a usuarios
        </a>
    </p>

    <hr>

    <p>
        <strong>ID:</strong>
        {{ $usuario->id_usuario }}
    </p>

    <p>
        <strong>Nombre:</strong>
        {{ $usuario->nombre }}
    </p>

    <p>
        <strong>Correo:</strong>
        {{ $usuario->correo }}
    </p>

    <p>
        <strong>Teléfono:</strong>
        {{ $usuario->telefono ?? '-' }}
    </p>

    <p>
        <strong>Rol:</strong>
        {{ $usuario->role->nombre ?? 'Sin rol' }}
    </p>

    <p>
        <strong>Estado:</strong>
        {{ $usuario->estado }}
    </p>

    <p>
        <strong>Registrado:</strong>
        {{ $usuario->created_at }}
    </p>

    <p>
        <strong>Última actualización:</strong>
        {{ $usuario->updated_at }}
    </p>

    <hr>

    <p>
        <a href="{{ route('admin.usuarios.edit', $usuario) }}">
            Editar usuario
        </a>
    </p>

</body>
</html>