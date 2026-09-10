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
                    <label for="id_categoria">Categoría</label>
                    <select id="id_categoria" name="id_categoria">
                        <option value="">-- Sin categoría --</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id_categoria }}" {{ old('id_categoria') == $categoria->id_categoria ? 'selected' : '' }}>
                                {{ $categoria->icono }} {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <small style="color: #666; font-size: 0.85rem;">Puedes seguir usando el campo texto abajo para compatibilidad</small>
                </div>
                <div class="form-group">
                    <label for="categoria">Categoría (texto)</label>
                    <input type="text" id="categoria" name="categoria"
                           value="{{ old('categoria') }}"
                           placeholder="Opcional si seleccionaste arriba" maxlength="255">
                </div>
            </div>

            <div class="form-grid">
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

            <hr style="margin: 30px 0; border: 1px solid #e0e0e0;">
            <h3 style="color: #00a84d; margin-bottom: 20px;">📋 Ficha de Caracterización</h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="tiempo_preparacion">Tiempo Preparación (min)</label>
                    <input type="number" id="tiempo_preparacion" name="tiempo_preparacion"
                           value="{{ old('tiempo_preparacion') }}" min="0" placeholder="Ej: 15">
                </div>
                <div class="form-group">
                    <label for="nivel_picante">Nivel Picante</label>
                    <select id="nivel_picante" name="nivel_picante">
                        <option value="ninguno" {{ old('nivel_picante') === 'ninguno' ? 'selected' : '' }}>Sin picante</option>
                        <option value="bajo" {{ old('nivel_picante') === 'bajo' ? 'selected' : '' }}>🌶️ Bajo</option>
                        <option value="medio" {{ old('nivel_picante') === 'medio' ? 'selected' : '' }}>🌶️🌶️ Medio</option>
                        <option value="alto" {{ old('nivel_picante') === 'alto' ? 'selected' : '' }}>🌶️🌶️🌶️ Alto</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="ingredientes_principales">Ingredientes Principales</label>
                <textarea id="ingredientes_principales" name="ingredientes_principales" 
                          placeholder="Ej: Pollo, arroz, verduras...">{{ old('ingredientes_principales') }}</textarea>
            </div>

            <h4 style="color: #00a84d; margin: 25px 0 15px;">🥗 Información Nutricional</h4>

            <div class="form-grid" style="grid-template-columns: repeat(4, 1fr);">
                <div class="form-group">
                    <label for="calorias">Calorías (kcal)</label>
                    <input type="number" id="calorias" name="calorias"
                           value="{{ old('calorias') }}" min="0" placeholder="Ej: 450">
                </div>
                <div class="form-group">
                    <label for="proteinas">Proteínas (g)</label>
                    <input type="number" step="0.1" id="proteinas" name="proteinas"
                           value="{{ old('proteinas') }}" min="0" placeholder="Ej: 25.5">
                </div>
                <div class="form-group">
                    <label for="carbohidratos">Carbohidratos (g)</label>
                    <input type="number" step="0.1" id="carbohidratos" name="carbohidratos"
                           value="{{ old('carbohidratos') }}" min="0" placeholder="Ej: 50.0">
                </div>
                <div class="form-group">
                    <label for="grasas">Grasas (g)</label>
                    <input type="number" step="0.1" id="grasas" name="grasas"
                           value="{{ old('grasas') }}" min="0" placeholder="Ej: 12.5">
                </div>
            </div>

            <h4 style="color: #00a84d; margin: 25px 0 15px;">🌱 Características Dietéticas</h4>

            <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="vegetariano" value="1" {{ old('vegetariano') ? 'checked' : '' }}>
                    <span>🥬 Vegetariano</span>
                </label>
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="vegano" value="1" {{ old('vegano') ? 'checked' : '' }}>
                    <span>🌱 Vegano</span>
                </label>
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="sin_gluten" value="1" {{ old('sin_gluten') ? 'checked' : '' }}>
                    <span>🌾 Sin Gluten</span>
                </label>
            </div>

            <div class="form-group">
                <label for="alergenos">⚠️ Alérgenos</label>
                <textarea id="alergenos" name="alergenos" rows="2"
                          placeholder="Ej: Contiene lácteos, frutos secos, gluten...">{{ old('alergenos') }}</textarea>
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
