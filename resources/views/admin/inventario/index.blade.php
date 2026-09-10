@extends('layouts.admin')

@section('title', 'Panel de Administrador - El Cielo')
@section('page_title', 'Inventario y Menú')

@section('content')

<!-- CARD 1: MENÚ -->
<div class="admin-card">
    <div class="admin-card-header">
        <div style="display: flex; align-items: center; gap: 10px;">
            <span class="title-accent-bar"></span>
            <h3 style="margin: 0; font-size: 1.2rem; font-weight: 800; color: var(--admin-text-dark);">Menú</h3>
        </div>
        <div style="display: flex; gap: 12px; align-items: center;">
            <button type="button" onclick="openModal('modalPlatilloDia')" class="btn-gold">
                ★ Platillo del Día
            </button>
            <button type="button" onclick="openModal('modalAgregarPlato')" class="btn-aplicar">
                + Agregar Plato
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr style="background-color: #f1f7f4;">
                    <th style="color: #0d9488; width: 80px;">IMAGEN</th>
                    <th style="color: #0d9488;">PLATO</th>
                    <th style="color: #0d9488;">CATEGORÍA</th>
                    <th style="color: #0d9488;">PRECIO</th>
                    <th style="color: #0d9488;">DISPONIBILIDAD</th>
                    <th style="color: #0d9488; text-align: right;">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                @forelse($platos as $plato)
                    <tr>
                        <td>
                            @if($plato->imagen)
                                <img src="{{ asset('storage/' . $plato->imagen) }}" alt="{{ $plato->nombre }}" style="width: 44px; height: 44px; border-radius: 10px; object-fit: cover;">
                            @else
                                <div style="width: 44px; height: 44px; border-radius: 10px; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">🍽️</div>
                            @endif
                        </td>
                        <td>
                            <strong style="color: var(--admin-text-dark); font-size: 0.95rem; display: block;">{{ strtolower($plato->nombre) }}</strong>
                            <small style="color: #94a3b8; font-weight: 500;">{{ Str::limit($plato->descripcion, 35) ?? 'sin descripción...' }}</small>
                        </td>
                        <td>
                            <span class="badge" style="background-color: #e0f2fe; color: #0369a1; text-transform: lowercase;">
                                {{ $plato->categoria ?? 'general' }}
                            </span>
                        </td>
                        <td style="font-weight: 800; color: var(--admin-text-dark);">${{ number_format($plato->precio, 2) }}</td>
                        <td>
                            @if($plato->disponibilidad > 0)
                                <span class="badge badge-success">✔ Disponible ({{ $plato->disponibilidad }})</span>
                            @else
                                <span class="badge badge-danger">✖ No disponible</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.platos.edit', $plato) }}" class="btn-link-action" style="color: #d97706;">📝 Editar</a>
                            <button type="button" onclick="openRecetaModal('{{ $plato->id_plato }}', '{{ addslashes($plato->nombre) }}')" class="btn-link-action" style="color: #0284c7; background: none; border: none; cursor: pointer; font-family: inherit;">📑 Receta</button>
                            <a href="#" onclick="openModal('modalPlatilloDia')" class="btn-link-action" style="color: #64748b;">🏷️ Oferta</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #94a3b8; padding: 36px 0; font-weight: 600;">
                            No hay platos registrados en el menú.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- CARD 2: MATERIA PRIMA (INVENTARIO FÍSICO) -->
<div class="admin-card">
    <div class="admin-card-header">
        <div style="display: flex; align-items: center; gap: 10px;">
            <span class="title-accent-bar"></span>
            <h3 style="margin: 0; font-size: 1.2rem; font-weight: 800; color: var(--admin-text-dark);">Materia Prima (Inventario Físico)</h3>
        </div>
        <button type="button" onclick="openModal('modalNuevaMateria')" class="btn-aplicar">
            + Nueva Materia Prima
        </button>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr style="background-color: #f1f7f4;">
                    <th style="color: #0d9488;">INGREDIENTE</th>
                    <th style="color: #0d9488;">STOCK ACTUAL</th>
                    <th style="color: #0d9488;">STOCK MÍNIMO</th>
                    <th style="color: #0d9488; text-align: right;">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materias as $materia)
                    <tr>
                        <td><strong style="color: var(--admin-text-dark);">{{ $materia->nombre }}</strong></td>
                        <td>
                            @if($materia->stock_actual <= $materia->stock_minimo)
                                <span class="badge badge-danger">{{ number_format($materia->stock_actual, 2) }} {{ $materia->unidad_medida }}</span>
                            @else
                                <span class="badge badge-success">{{ number_format($materia->stock_actual, 2) }} {{ $materia->unidad_medida }}</span>
                            @endif
                        </td>
                        <td style="font-weight: 600; color: #64748b;">{{ number_format($materia->stock_minimo, 2) }} {{ $materia->unidad_medida }}</td>
                        <td style="text-align: right;">
                            <form method="POST" action="{{ route('admin.inventario.materias.stock', $materia) }}" style="display: inline-flex; gap: 6px; align-items: center;">
                                @csrf
                                <input type="number" name="cantidad_a_sumar" step="0.01" min="0.01" placeholder="Cant." required class="filter-input" style="width: 80px; padding: 4px 8px; font-size: 0.8rem;">
                                <button type="submit" class="btn-aplicar" style="padding: 6px 12px; font-size: 0.8rem;">+ Stock</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #94a3b8; padding: 36px 0; font-weight: 600;">
                            No hay materias primas registradas
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ============================================================================
     MODALES (SCREENSHOTS 2, 3 Y 4)
     ============================================================================ -->

<!-- MODAL 1: NUEVA MATERIA PRIMA (SCREENSHOT 2) -->
<div class="modal-overlay" id="modalNuevaMateria">
    <div class="modal-card">
        <div class="modal-header">
            <h3 class="modal-title">Nueva Materia Prima</h3>
            <button type="button" class="modal-close-btn" onclick="closeModal('modalNuevaMateria')">✕</button>
        </div>

        <form method="POST" action="{{ route('admin.inventario.materias.store') }}">
            @csrf
            <div class="filter-group" style="margin-bottom: 16px;">
                <label class="filter-label">Nombre del Ingrediente</label>
                <input type="text" name="nombre" class="filter-input" placeholder="Ej. Tomate, Carne de Res, Sal" required>
            </div>

            <div class="form-row-2" style="margin-bottom: 16px;">
                <div class="filter-group">
                    <label class="filter-label">Stock Inicial</label>
                    <input type="number" step="0.01" min="0" name="stock_actual" class="filter-input" placeholder="0.00" value="0.00" required>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Stock Mínimo (Alerta)</label>
                    <input type="number" step="0.01" min="0" name="stock_minimo" class="filter-input" placeholder="0.00" value="0.00" required>
                </div>
            </div>

            <div class="filter-group" style="margin-bottom: 24px;">
                <label class="filter-label">Unidad de Medida</label>
                <select name="unidad_medida" class="filter-select" required>
                    <option value="Gramos (g)">Gramos (g)</option>
                    <option value="Kilogramos (kg)">Kilogramos (kg)</option>
                    <option value="Litros (l)">Litros (l)</option>
                    <option value="Mililitros (ml)">Mililitros (ml)</option>
                    <option value="Unidades (ud)">Unidades (ud)</option>
                    <option value="Porciones">Porciones</option>
                    <option value="Paquetes">Paquetes</option>
                </select>
            </div>

            <button type="submit" class="btn-aplicar" style="width: 100%; justify-content: center; padding: 12px; font-size: 1rem;">
                Guardar Ingrediente
            </button>
        </form>
    </div>
</div>

<!-- MODAL 2: CREAR PLATILLO ESPECIAL DEL DÍA (SCREENSHOT 3) -->
<div class="modal-overlay" id="modalPlatilloDia">
    <div class="modal-card">
        <div class="modal-header">
            <h3 class="modal-title" style="color: #d97706;">★ Crear Platillo Especial del Día</h3>
            <button type="button" class="modal-close-btn" onclick="closeModal('modalPlatilloDia')">✕</button>
        </div>

        <p class="modal-subtitle">
            Crea un platillo único que solo estará disponible durante el período que definas. Aparecerá destacado en el panel del cliente.
        </p>

        <form method="POST" action="{{ route('admin.platos.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="filter-group" style="margin-bottom: 16px;">
                <label class="filter-label">Nombre del platillo especial</label>
                <input type="text" name="nombre" class="filter-input" placeholder="Ej. Bandeja Paisa Especial" required>
            </div>

            <div class="form-row-2" style="margin-bottom: 16px;">
                <div class="filter-group">
                    <label class="filter-label">Precio normal ($)</label>
                    <input type="number" step="0.01" min="0" name="precio" class="filter-input" placeholder="Ej. 18.00" required>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Precio en oferta ($) (opcional)</label>
                    <input type="number" step="0.01" min="0" name="precio_oferta" class="filter-input" placeholder="Ej. 14.00">
                </div>
            </div>

            <div class="filter-group" style="margin-bottom: 16px;">
                <label class="filter-label">Descripción</label>
                <textarea name="descripcion" class="filter-input" style="height: 70px; resize: vertical;" placeholder="Ingredientes o descripción breve"></textarea>
            </div>

            <div class="form-row-2" style="margin-bottom: 16px;">
                <div class="filter-group">
                    <label class="filter-label">Categoría</label>
                    <input type="text" name="categoria" class="filter-input" value="Especial">
                </div>
                <div class="filter-group">
                    <label class="filter-label">Etiqueta de oferta</label>
                    <input type="text" name="etiqueta_oferta" class="filter-input" value="Platillo del Día">
                </div>
            </div>

            <div class="form-row-2" style="margin-bottom: 16px;">
                <div class="filter-group">
                    <label class="filter-label">Disponible desde (vacío = ahora)</label>
                    <input type="datetime-local" name="disponible_desde" class="filter-input">
                </div>
                <div class="filter-group">
                    <label class="filter-label">Disponible hasta (vacío = sin límite)</label>
                    <input type="datetime-local" name="disponible_hasta" class="filter-input">
                </div>
            </div>

            <div class="filter-group" style="margin-bottom: 24px;">
                <label class="filter-label">Imagen (opcional)</label>
                <input type="file" name="imagen_file" class="filter-input" accept="image/*">
            </div>

            <button type="submit" class="btn-gold" style="width: 100%; justify-content: center; padding: 12px; font-size: 1rem;">
                ★ Crear Platillo Especial
            </button>
        </form>
    </div>
</div>

<!-- MODAL 3: AGREGAR PLATO AL MENÚ (SCREENSHOT 4) -->
<div class="modal-overlay" id="modalAgregarPlato">
    <div class="modal-card">
        <div class="modal-header">
            <h3 class="modal-title">Agregar Plato al Menú</h3>
            <button type="button" class="modal-close-btn" onclick="closeModal('modalAgregarPlato')">✕</button>
        </div>

        <form method="POST" action="{{ route('admin.platos.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="filter-group" style="margin-bottom: 16px;">
                <label class="filter-label">Nombre del Plato</label>
                <input type="text" name="nombre" class="filter-input" placeholder="Ej. Hamburguesa Doble" required>
            </div>

            <div class="form-row-2" style="margin-bottom: 16px;">
                <div class="filter-group">
                    <label class="filter-label">Precio ($)</label>
                    <input type="number" step="0.01" min="0" name="precio" class="filter-input" placeholder="Ej. 15.50" required>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Categoría</label>
                    <input type="text" name="categoria" class="filter-input" placeholder="Ej. Platos Fuertes" required>
                </div>
            </div>

            <div class="filter-group" style="margin-bottom: 16px;">
                <label class="filter-label">Descripción corta</label>
                <textarea name="descripcion" class="filter-input" style="height: 70px; resize: vertical;" placeholder="Ingredientes o descripción breve"></textarea>
            </div>

            <div class="filter-group" style="margin-bottom: 24px;">
                <label class="filter-label">Imagen del Plato</label>
                <input type="file" name="imagen_file" class="filter-input" accept="image/*">
            </div>

            <button type="submit" class="btn-aplicar" style="width: 100%; justify-content: center; padding: 12px; font-size: 1rem;">
                Guardar Plato
            </button>
        </form>
    </div>
</div>

<!-- MODAL 4: RECETA / INGREDIENTES DEL PLATO -->
<div class="modal-overlay" id="modalReceta">
    <div class="modal-card">
        <div class="modal-header">
            <h3 class="modal-title">📑 Ingredientes de Receta: <span id="modalRecetaPlatoNombre" style="color: #0284c7;"></span></h3>
            <button type="button" class="modal-close-btn" onclick="closeModal('modalReceta')">✕</button>
        </div>

        <form method="POST" action="{{ route('admin.inventario.recetas.store') }}">
            @csrf
            <input type="hidden" name="id_plato" id="modalRecetaIdPlato">

            <div class="filter-group" style="margin-bottom: 16px;">
                <label class="filter-label">Seleccionar Materia Prima / Ingrediente</label>
                <select name="id_materia" class="filter-select" required>
                    <option value="">Seleccionar ingrediente...</option>
                    @foreach($materias as $m)
                        <option value="{{ $m->id_materia }}">{{ $m->nombre }} ({{ $m->unidad_medida }})</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group" style="margin-bottom: 24px;">
                <label class="filter-label">Cantidad Requerida por Porción</label>
                <input type="number" step="0.01" min="0.01" name="cantidad_requerida" class="filter-input" placeholder="Ej. 250" required>
            </div>

            <button type="submit" class="btn-aplicar" style="width: 100%; justify-content: center; padding: 12px; font-size: 1rem; background-color: #0284c7;">
                Agregar Ingrediente a Receta
            </button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('show');
        }
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('show');
        }
    }

    function openRecetaModal(idPlato, nombrePlato) {
        document.getElementById('modalRecetaIdPlato').value = idPlato;
        document.getElementById('modalRecetaPlatoNombre').innerText = nombrePlato;
        openModal('modalReceta');
    }

    // Close on overlay click
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            e.target.classList.remove('show');
        }
    });
</script>
@endpush
