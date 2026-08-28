<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Plato — El Cielo</title>
    <link rel="stylesheet" href="{{ asset('css/admin-platos.css') }}">
</head>
<body>

<header>
    <h1>El Cielo</h1>
</header>

<main style="max-width: 900px;">

    <div class="top-bar">
        <h2>Nuevo plato</h2>
        <a href="{{ route('admin.platos.index') }}">← Volver a platos</a>
    </div>

    @if($errors->any())
        <div class="errors">
            <strong>Hay errores en el formulario:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <h2>Registrar nuevo plato</h2>

        <form method="POST"
              action="{{ route('admin.platos.store') }}"
              enctype="multipart/form-data">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre"
                           value="{{ old('nombre') }}" required maxlength="255">
                </div>
                <div class="form-group">
                    <label for="precio">Precio</label>
                    <input type="number" id="precio" name="precio"
                           value="{{ old('precio') }}" min="0" step="0.01" required>
                </div>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion"
                          placeholder="Descripción del plato...">{{ old('descripcion') }}</textarea>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="categoria">Categoría</label>
                    <input type="text" id="categoria" name="categoria"
                           value="{{ old('categoria') }}"
                           placeholder="Ej: carnes, bebidas, entradas..." maxlength="255">
                </div>
                <div class="form-group">
                    <label for="disponibilidad">Disponibilidad</label>
                    <input type="number" id="disponibilidad" name="disponibilidad"
                           value="{{ old('disponibilidad', 1) }}" min="0" step="1" required>
                </div>
            </div>

            <div class="form-group">
                <label for="imagen">Imagen</label>
                <input type="file" id="imagen" name="imagen" accept="image/*">
            </div>

            <div>
                <button type="submit" class="btn-submit">+ Crear plato</button>
                <a href="{{ route('admin.platos.index') }}" class="btn-cancel">Cancelar</a>
            </div>

        </form>
    </div>

</main>

</body>
</html>
