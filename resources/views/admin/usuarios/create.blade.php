<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Crear usuario - StockYServe</title>
    <link rel="icon" href="{{ asset('images/logo-circle.png') }}?v={{ time() }}" type="image/png">
</head>

<body>

    <h1>StockYServe</h1>

    <h2>Crear usuario</h2>

    <p>
        <a href="{{ route('admin.usuarios.index') }}">
            ← Volver a usuarios
        </a>
    </p>

    @if ($errors->any())
        <div>
            <strong>Hay errores en el formulario:</strong>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('admin.usuarios.store') }}"
        method="POST"
    >

        @csrf

        <p>
            <label for="nombre">
                Nombre:
            </label>

            <br>

            <input
                type="text"
                id="nombre"
                name="nombre"
                value="{{ old('nombre') }}"
                required
            >
        </p>

        <p>
            <label for="correo">
                Correo:
            </label>

            <br>

            <input
                type="email"
                id="correo"
                name="correo"
                value="{{ old('correo') }}"
                required
            >
        </p>

        <p>
            <label for="contrasena">
                Contraseña:
            </label>

            <br>

            <input
                type="password"
                id="contrasena"
                name="contrasena"
                required
            >

            <br>

            <small>
                Mínimo 8 caracteres.
            </small>
        </p>

        <p>
            <label for="telefono">
                Teléfono:
            </label>

            <br>

            <input
                type="text"
                id="telefono"
                name="telefono"
                value="{{ old('telefono') }}"
            >
        </p>

        <p>
            <label for="id_rol">
                Rol:
            </label>

            <br>

            <select
                id="id_rol"
                name="id_rol"
                required
            >

                <option value="">
                    -- Seleccione un rol --
                </option>

                @foreach ($roles as $rol)

                    <option
                        value="{{ $rol->id_rol }}"
                        {{ old('id_rol') == $rol->id_rol ? 'selected' : '' }}
                    >
                        {{ $rol->nombre }}
                    </option>

                @endforeach

            </select>
        </p>

        <p>
            <label for="estado">
                Estado:
            </label>

            <br>

            <select
                id="estado"
                name="estado"
                required
            >

                <option
                    value="activo"
                    {{ old('estado', 'activo') === 'activo' ? 'selected' : '' }}
                >
                    Activo
                </option>

                <option
                    value="inactivo"
                    {{ old('estado') === 'inactivo' ? 'selected' : '' }}
                >
                    Inactivo
                </option>

            </select>
        </p>

        <button type="submit">
            Crear usuario
        </button>

    </form>

</body>
</html>