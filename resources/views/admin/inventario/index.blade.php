<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario — El Cielo</title>
    <link rel="stylesheet" href="{{ asset('css/admin-inventario.css') }}">
</head>
<body>

<header>
    <h1>El Cielo</h1>
</header>

<main>

    <div class="top-bar">
        <h2>Gestión de Inventario</h2>
        <a href="{{ route('admin.dashboard') }}">← Volver al panel</a>
    </div>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

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

    {{-- ── CREAR MATERIA PRIMA ── --}}
    <div class="card">
        <h2>Nueva materia prima</h2>

        <form method="POST" action="{{ route('admin.inventario.materias.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre"
                           value="{{ old('nombre') }}" required>
                </div>
                <div class="form-group">
                    <label for="stock_actual">Stock actual</label>
                    <input type="number" step="0.01" min="0" id="stock_actual"
                           name="stock_actual" value="{{ old('stock_actual', 0) }}" required>
                </div>
                <div class="form-group">
                    <label for="unidad_medida">Unidad de medida</label>
                    <input type="text" id="unidad_medida" name="unidad_medida"
                           placeholder="kg, litros, unidades..."
                           value="{{ old('unidad_medida') }}" required>
                </div>
                <div class="form-group">
                    <label for="stock_minimo">Stock mínimo</label>
                    <input type="number" step="0.01" min="0" id="stock_minimo"
                           name="stock_minimo" value="{{ old('stock_minimo', 0) }}" required>
                </div>
            </div>
            <br>
            <button type="submit" class="btn-success">+ Crear materia prima</button>
        </form>
    </div>

    {{-- ── LISTADO MATERIAS PRIMAS ── --}}
    <div class="card">
        <h2>Materias primas</h2>

        @if($materias->isEmpty())
            <p>No hay materias primas registradas.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Stock actual</th>
                        <th>Unidad</th>
                        <th>Stock mínimo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materias as $materia)
                        {{-- Fila principal --}}
                        <tr>
                            <td>{{ $materia->id_materia }}</td>
                            <td>{{ $materia->nombre }}</td>
                            <td class="{{ $materia->stock_actual <= $materia->stock_minimo ? 'stock-bajo' : '' }}">
                                {{ $materia->stock_actual }}
                            </td>
                            <td>{{ $materia->unidad_medida }}</td>
                            <td>{{ $materia->stock_minimo }}</td>
                            <td>
                                <div class="actions">
                                    <a href="#editar-{{ $materia->id_materia }}">
                                        <button type="button" class="btn-warning">Editar</button>
                                    </a>
                                    <form method="POST"
                                          action="{{ route('admin.inventario.materias.stock', $materia) }}">
                                        @csrf
                                        <input type="number" name="cantidad_a_sumar"
                                               step="0.01" min="0.01"
                                               placeholder="Cantidad" required
                                               class="stock-input">
                                        <button type="submit">+ Stock</button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Fila de edición inline --}}
                        <tr id="editar-{{ $materia->id_materia }}" class="edit-row">
                            <td colspan="6">
                                <h3>Editar: {{ $materia->nombre }}</h3>
                                <form method="POST"
                                      action="{{ route('admin.inventario.materias.update', $materia) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-grid">
                                        <div class="form-group">
                                            <label>Nombre</label>
                                            <input type="text" name="nombre"
                                                   value="{{ $materia->nombre }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Stock actual</label>
                                            <input type="number" name="stock_actual"
                                                   step="0.01" min="0"
                                                   value="{{ $materia->stock_actual }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Unidad de medida</label>
                                            <input type="text" name="unidad_medida"
                                                   value="{{ $materia->unidad_medida }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Stock mínimo</label>
                                            <input type="number" name="stock_minimo"
                                                   step="0.01" min="0"
                                                   value="{{ $materia->stock_minimo }}" required>
                                        </div>
                                    </div>
                                    <br>
                                    <button type="submit" class="btn-success">Guardar cambios</button>
                                    <a href="{{ route('admin.inventario.index') }}"
                                       style="margin-left:10px; color:#2563eb; font-size:14px;">
                                        Cancelar
                                    </a>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- ── AGREGAR INGREDIENTE A RECETA ── --}}
    <div class="card">
        <h2>Agregar ingrediente a una receta</h2>

        @if($platos->isEmpty())
            <p>No hay platos registrados todavía.</p>
        @elseif($materias->isEmpty())
            <p>No hay materias primas registradas todavía.</p>
        @else
            <form method="POST" action="{{ route('admin.inventario.recetas.store') }}">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label for="id_plato">Plato</label>
                        <select id="id_plato" name="id_plato" required>
                            <option value="">Seleccionar plato</option>
                            @foreach($platos as $plato)
                                <option value="{{ $plato->id_plato }}">{{ $plato->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="id_materia">Ingrediente</label>
                        <select id="id_materia" name="id_materia" required>
                            <option value="">Seleccionar ingrediente</option>
                            @foreach($materias as $materia)
                                <option value="{{ $materia->id_materia }}">{{ $materia->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="cantidad_requerida">Cantidad requerida</label>
                        <input type="number" step="0.01" min="0.01"
                               id="cantidad_requerida" name="cantidad_requerida" required>
                    </div>
                    <div class="form-group" style="justify-content:flex-end;">
                        <button type="submit">Agregar ingrediente</button>
                    </div>
                </div>
            </form>
        @endif
    </div>

    {{-- ── RECETAS ── --}}
    <div class="card">
        <h2>Recetas de los platos</h2>

        @if($platos->isEmpty())
            <p>No hay platos registrados todavía.</p>
        @else
            @foreach($platos as $plato)
                <div class="recipe-block">
                    <h3>{{ $plato->nombre }}</h3>

                    @if($plato->recetas->isEmpty())
                        <p>Este plato todavía no tiene ingredientes registrados.</p>
                    @else
                        <table>
                            <thead>
                                <tr>
                                    <th>Ingrediente</th>
                                    <th>Cantidad requerida</th>
                                    <th>Unidad</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($plato->recetas as $receta)
                                    <tr>
                                        <td>{{ $receta->materiaPrima->nombre }}</td>
                                        <td>{{ $receta->cantidad_requerida }}</td>
                                        <td>{{ $receta->materiaPrima->unidad_medida }}</td>
                                        <td>
                                            <form method="POST"
                                                  action="{{ route('admin.inventario.recetas.destroy', $receta) }}"
                                                  onsubmit="return confirm('¿Eliminar este ingrediente de la receta?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-danger">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            @endforeach
        @endif
    </div>

</main>

</body>
</html>
