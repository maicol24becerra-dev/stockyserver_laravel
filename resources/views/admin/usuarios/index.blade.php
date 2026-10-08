@extends('layouts.admin')

@section('title', 'Panel de Administrador - El Cielo')
@section('page_title', 'Gestión de Usuarios')

@section('content')

<div class="admin-card">
    <div class="admin-card-header">
        <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800;">Listado de Usuarios</h3>
        <a href="{{ route('admin.usuarios.create') }}" class="btn-aplicar" style="text-decoration: none;">
            + Nuevo Usuario
        </a>
    </div>

    @if ($usuarios->count() > 0)
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th style="text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuarios as $usuario)
                        <tr>
                            <td><strong>#{{ $usuario->id_usuario }}</strong></td>
                            <td><strong>{{ $usuario->nombre }}</strong></td>
                            <td>{{ $usuario->correo }}</td>
                            <td>{{ $usuario->telefono ?? '-' }}</td>
                            <td>
                                <span class="badge badge-success">{{ $usuario->role->nombre ?? 'Sin rol' }}</span>
                            </td>
                            <td>
                                @if(strtolower($usuario->estado) === 'activo')
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-danger">{{ ucfirst($usuario->estado) }}</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <button type="button" onclick="abrirModalVerUsuario({{ $usuario->id_usuario }}, '{{ addslashes($usuario->nombre) }}', '{{ addslashes($usuario->correo) }}', '{{ addslashes($usuario->telefono) }}', '{{ addslashes($usuario->role->nombre ?? '') }}', '{{ addslashes($usuario->estado) }}')" style="background: none; border: none; color: #0d9488; font-weight: 700; cursor: pointer; font-family: inherit; margin-right: 10px; text-decoration: none;">Ver</button>
                                <button type="button" onclick="abrirModalEditarUsuario({{ $usuario->id_usuario }}, '{{ addslashes($usuario->nombre) }}', '{{ addslashes($usuario->correo) }}', '{{ addslashes($usuario->telefono) }}', '{{ $usuario->id_rol }}', '{{ addslashes($usuario->estado) }}')" style="background: none; border: none; color: #2563eb; font-weight: 700; cursor: pointer; font-family: inherit; margin-right: 10px; text-decoration: none;">Editar</button>
                                @if (auth()->id() !== $usuario->id_usuario)
                                    <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: none; border: none; color: #ef4444; font-weight: 700; cursor: pointer; font-family: inherit;">Eliminar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $usuarios->links() }}
        </div>
    @else
        <p style="text-align: center; color: #94a3b8; padding: 40px 0;">No hay usuarios registrados.</p>
    @endif
</div>

<!-- MODAL: VER USUARIO -->
<div class="modal-overlay" id="modalVerUsuario">
    <div class="modal-card">
        <div class="modal-header">
            <h3 class="modal-title">Detalles del Usuario</h3>
            <button type="button" class="modal-close-btn" onclick="closeModal('modalVerUsuario')">✕</button>
        </div>

        <div style="padding: 16px;">
            <div class="filter-group" style="margin-bottom: 12px;">
                <label class="filter-label">Nombre</label>
                <p style="margin: 0; color: #1f2937; font-weight: 500;" id="verNombreUsuario">-</p>
            </div>

            <div class="filter-group" style="margin-bottom: 12px;">
                <label class="filter-label">Correo</label>
                <p style="margin: 0; color: #1f2937; font-weight: 500;" id="verCorreoUsuario">-</p>
            </div>

            <div class="filter-group" style="margin-bottom: 12px;">
                <label class="filter-label">Teléfono</label>
                <p style="margin: 0; color: #1f2937; font-weight: 500;" id="verTelefonoUsuario">-</p>
            </div>

            <div class="filter-group" style="margin-bottom: 12px;">
                <label class="filter-label">Rol</label>
                <p style="margin: 0; color: #1f2937; font-weight: 500;" id="verRolUsuario">-</p>
            </div>

            <div class="filter-group" style="margin-bottom: 18px;">
                <label class="filter-label">Estado</label>
                <p style="margin: 0; color: #1f2937; font-weight: 500;" id="verEstadoUsuario">-</p>
            </div>

            <button type="button" class="btn-aplicar" onclick="closeModal('modalVerUsuario')" style="width: 100%; justify-content: center; padding: 10px; font-size: 0.95rem;">
                Cerrar
            </button>
        </div>
    </div>
</div>

<!-- MODAL: EDITAR USUARIO -->
<div class="modal-overlay" id="modalEditarUsuario">
    <div class="modal-card">
        <div class="modal-header">
            <h3 class="modal-title">Editar Usuario</h3>
            <button type="button" class="modal-close-btn" onclick="closeModal('modalEditarUsuario')">✕</button>
        </div>

        <form method="POST" id="formEditarUsuario">
            @csrf
            @method('PUT')

            <div class="filter-group" style="margin-bottom: 12px;">
                <label class="filter-label">Nombre *</label>
                <input type="text" id="editNombreUsuario" name="nombre" class="filter-input" required>
            </div>

            <div class="filter-group" style="margin-bottom: 12px;">
                <label class="filter-label">Correo *</label>
                <input type="email" id="editCorreoUsuario" name="correo" class="filter-input" required>
            </div>

            <div class="filter-group" style="margin-bottom: 12px;">
                <label class="filter-label">Teléfono</label>
                <input type="text" id="editTelefonoUsuario" name="telefono" class="filter-input">
            </div>

            <div class="filter-group" style="margin-bottom: 12px;">
                <label class="filter-label">Rol *</label>
                <select id="editRolUsuario" name="id_rol" class="filter-select" required>
                    <option value="">Seleccionar rol...</option>
                    @foreach($roles ?? [] as $rol)
                        <option value="{{ $rol->id_rol }}">{{ $rol->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group" style="margin-bottom: 18px;">
                <label class="filter-label">Estado *</label>
                <select id="editEstadoUsuario" name="estado" class="filter-select" required>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>

            <button type="submit" class="btn-aplicar" style="width: 100%; justify-content: center; padding: 10px; font-size: 0.95rem;">
                ✓ Actualizar
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

    function abrirModalVerUsuario(id, nombre, correo, telefono, rol, estado) {
        // Llenar los datos en el modal
        document.getElementById('verNombreUsuario').innerText = nombre || '-';
        document.getElementById('verCorreoUsuario').innerText = correo || '-';
        document.getElementById('verTelefonoUsuario').innerText = telefono || '-';
        document.getElementById('verRolUsuario').innerText = rol || '-';
        document.getElementById('verEstadoUsuario').innerText = estado ? (estado.charAt(0).toUpperCase() + estado.slice(1)) : '-';
        
        // Abrir el modal
        openModal('modalVerUsuario');
    }

    function abrirModalEditarUsuario(id, nombre, correo, telefono, idRol, estado) {
        // Llenar los datos en el formulario
        document.getElementById('editNombreUsuario').value = nombre;
        document.getElementById('editCorreoUsuario').value = correo;
        document.getElementById('editTelefonoUsuario').value = telefono || '';
        document.getElementById('editRolUsuario').value = idRol || '';
        document.getElementById('editEstadoUsuario').value = estado.toLowerCase() || 'activo';
        
        // Configurar la acción del formulario para actualizar (ruta PUT)
        const form = document.getElementById('formEditarUsuario');
        form.action = '/admin/usuarios/' + id;
        
        // Abrir el modal
        openModal('modalEditarUsuario');
    }

    // Close on overlay click
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            e.target.classList.remove('show');
        }
    });
</script>
@endpush