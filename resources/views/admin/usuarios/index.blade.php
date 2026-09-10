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
                                <a href="{{ route('admin.usuarios.show', $usuario) }}" style="color: #0d9488; font-weight: 700; text-decoration: none; margin-right: 10px;">Ver</a>
                                <a href="{{ route('admin.usuarios.edit', $usuario) }}" style="color: #2563eb; font-weight: 700; text-decoration: none; margin-right: 10px;">Editar</a>
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

@endsection