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
                                <img src="{{ \Illuminate\Support\Facades\URL::to('/storage/' . $plato->imagen) }}" alt="{{ $plato->nombre }}" style="width: 44px; height: 44px; border-radius: 10px; object-fit: cover;" onerror="this.parentElement.innerHTML='<div style=\"width: 44px; height: 44px; border-radius: 10px; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;\">🍽️</div>'">
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
                            <button type="button" onclick="abrirModalEditar({{ $plato->id_plato }}, '{{ addslashes($plato->nombre) }}', {{ $plato->precio }}, {{ $plato->disponibilidad }}, '{{ addslashes($plato->categoria) }}', '{{ addslashes($plato->descripcion) }}')" class="btn-link-action" style="color: #d97706; background: none; border: none; cursor: pointer; font-family: inherit;">📝 Editar</button>
                            <button type="button" onclick="openRecetaModal('{{ $plato->id_plato }}', '{{ addslashes($plato->nombre) }}')" class="btn-link-action" style="color: #0284c7; background: none; border: none; cursor: pointer; font-family: inherit;">📑 Receta</button>
                        </td>
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

<!-- MODAL 2: AGREGAR PLATO AL MENÚ (SCREENSHOT 3) -->
<div class="modal-overlay" id="modalAgregarPlato">
    <div class="modal-card">
        <div class="modal-header">
            <h3 class="modal-title">Agregar Plato al Menú</h3>
            <button type="button" class="modal-close-btn" onclick="closeModal('modalAgregarPlato')">✕</button>
        </div>

        <form method="POST" action="{{ route('admin.platos.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="filter-group" style="margin-bottom: 16px;">
                <label class="filter-label">Nombre del Plato *</label>
                <input type="text" name="nombre" class="filter-input" placeholder="Ej. Hamburguesa Doble" required>
            </div>

            <div class="form-row-2" style="margin-bottom: 16px;">
                <div class="filter-group">
                    <label class="filter-label">Precio ($) *</label>
                    <input type="number" step="0.01" min="0" name="precio" class="filter-input" placeholder="15.50" required>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Disponibilidad *</label>
                    <input type="number" min="0" name="disponibilidad" class="filter-input" placeholder="10" value="1" required>
                </div>
            </div>

            <div class="filter-group" style="margin-bottom: 16px;">
                <label class="filter-label">Categoría</label>
                <input type="text" name="categoria" class="filter-input" placeholder="Ej. Platos Fuertes">
            </div>

            <div class="filter-group" style="margin-bottom: 16px;">
                <label class="filter-label">Descripción</label>
                <textarea name="descripcion" class="filter-input" style="height: 60px; resize: vertical;" placeholder="Ingredientes o descripción breve"></textarea>
            </div>

            <div class="filter-group" style="margin-bottom: 24px;">
                <label class="filter-label">Imagen</label>
                <input type="file" name="imagen" class="filter-input" accept="image/*">
                <small style="color: #666; display: block; margin-top: 8px;">JPG, PNG o GIF (máx 2MB)</small>
            </div>

            <button type="submit" class="btn-aplicar" style="width: 100%; justify-content: center; padding: 12px; font-size: 1rem;">
                ✓ Guardar Plato
            </button>
        </form>
    </div>
</div>

<!-- MODAL 3: EDITAR PLATO -->
<div class="modal-overlay" id="modalEditarPlato">
    <div class="modal-card">
        <div class="modal-header">
            <h3 class="modal-title">Editar Plato</h3>
            <button type="button" class="modal-close-btn" onclick="closeModal('modalEditarPlato')">✕</button>
        </div>

        <form method="POST" id="formEditarPlato" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="filter-group" style="margin-bottom: 12px;">
                <label class="filter-label">Nombre *</label>
                <input type="text" id="editNombre" name="nombre" class="filter-input" required>
            </div>

            <div class="form-row-2" style="margin-bottom: 12px;">
                <div class="filter-group">
                    <label class="filter-label">Precio ($) *</label>
                    <input type="number" step="0.01" min="0" id="editPrecio" name="precio" class="filter-input" required>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Disponibilidad *</label>
                    <input type="number" min="0" id="editDisponibilidad" name="disponibilidad" class="filter-input" required>
                </div>
            </div>

            <div class="filter-group" style="margin-bottom: 12px;">
                <label class="filter-label">Categoría</label>
                <input type="text" id="editCategoria" name="categoria" class="filter-input">
            </div>

            <div class="filter-group" style="margin-bottom: 12px;">
                <label class="filter-label">Descripción</label>
                <textarea id="editDescripcion" name="descripcion" class="filter-input" style="height: 50px; resize: vertical;"></textarea>
            </div>

            <div class="filter-group" style="margin-bottom: 18px;">
                <label class="filter-label">Imagen</label>
                <input type="file" id="editImagen" name="imagen" class="filter-input" accept="image/*">
                <small style="color: #666; display: block; margin-top: 6px;">JPG, PNG o GIF (máx 2MB)</small>
            </div>

            <button type="submit" class="btn-aplicar" style="width: 100%; justify-content: center; padding: 10px; font-size: 0.95rem;">
                ✓ Actualizar
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

    function abrirModalEditar(id, nombre, precio, disponibilidad, categoria, descripcion) {
        // Llenar los datos en el formulario
        document.getElementById('editNombre').value = nombre;
        document.getElementById('editPrecio').value = precio;
        document.getElementById('editDisponibilidad').value = disponibilidad;
        document.getElementById('editCategoria').value = categoria || '';
        document.getElementById('editDescripcion').value = descripcion || '';
        
        // Configurar la acción del formulario para actualizar (ruta PUT)
        const form = document.getElementById('formEditarPlato');
        form.action = '/admin/platos/' + id;
        
        // Abrir el modal
        openModal('modalEditarPlato');
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
