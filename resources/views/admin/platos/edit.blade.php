<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Plato — El Cielo</title>
    <link rel="icon" href="{{ asset('images/logo-circle.png') }}?v={{ time() }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/admin-platos.css') }}">
</head>
<body>

<header>
    <h1>El Cielo</h1>
</header>

<main style="max-width: 900px;">

    <div class="top-bar">
        <h2>Editar plato: {{ $plato->nombre }}</h2>
        <a href="{{ route('admin.inventario.index') }}">← Volver al inventario</a>
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
        <h2>Actualizar Plato</h2>

        <form method="POST"
              action="{{ route('admin.platos.update', $plato) }}"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre"
                           value="{{ old('nombre', $plato->nombre) }}" required maxlength="255">
                </div>
                <div class="form-group">
                    <label for="precio">Precio</label>
                    <input type="number" id="precio" name="precio"
                           value="{{ old('precio', $plato->precio) }}" min="0" step="0.01" required>
                </div>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion"
                          placeholder="Descripción del plato...">{{ old('descripcion', $plato->descripcion) }}</textarea>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="categoria">Categoría</label>
                    <input type="text" id="categoria" name="categoria"
                           value="{{ old('categoria', $plato->categoria) }}"
                           placeholder="Ej: Carnes, Vegetales, etc." maxlength="255">
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="disponibilidad">Disponibilidad</label>
                    <input type="number" id="disponibilidad" name="disponibilidad"
                           value="{{ old('disponibilidad', $plato->disponibilidad) }}" min="0" step="1" required>
                </div>
            </div>

            <div class="form-group">
                <label for="imagen">Imagen</label>
                @if($plato->imagen)
                    <div style="margin-bottom: 12px;">
                        <img src="{{ \Illuminate\Support\Facades\URL::to('/storage/' . $plato->imagen) }}" alt="{{ $plato->nombre }}" 
                             style="max-width: 150px; max-height: 150px; border-radius: 8px; object-fit: cover;">
                        <p style="font-size: 0.85rem; color: #666; margin-top: 8px;">Imagen actual</p>
                    </div>
                @endif
                <input type="file" id="imagen" name="imagen" accept="image/*">
                <small style="color: #666;">Dejar vacío para mantener la imagen actual</small>
            </div>

            <div>
                <button type="submit" class="btn-submit">💾 Actualizar plato</button>
                <a href="{{ route('admin.inventario.index') }}" class="btn-cancel">Cancelar</a>
            </div>

        </form>
    </div>

</main>

</body>
</html>
