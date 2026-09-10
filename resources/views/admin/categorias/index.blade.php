@extends('layouts.admin')

@section('title', 'Categorías')

@section('content')
<div class="page-header">
    <h1>Gestión de Categorías</h1>
    <a href="{{ route('admin.categorias.create') }}" class="btn btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Nueva Categoría
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<div class="card">
    <div class="card-body">
        @if($categorias->count())
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Icono</th>
                            <th>Platos</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categorias as $categoria)
                            <tr>
                                <td>{{ $categoria->id_categoria }}</td>
                                <td><strong>{{ $categoria->nombre }}</strong></td>
                                <td>{{ Str::limit($categoria->descripcion, 50) ?? '-' }}</td>
                                <td>
                                    @if($categoria->icono)
                                        <span style="font-size: 1.5rem;">{{ $categoria->icono }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $categoria->platos_count }} platos</span>
                                </td>
                                <td>
                                    @if($categoria->activo)
                                        <span class="badge badge-success">Activa</span>
                                    @else
                                        <span class="badge badge-secondary">Inactiva</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.categorias.show', $categoria) }}" class="btn btn-sm btn-info" title="Ver">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.categorias.edit', $categoria) }}" class="btn btn-sm btn-warning" title="Editar">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.categorias.destroy', $categoria) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar esta categoría?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <polyline points="3 6 5 6 21 6"/>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $categorias->links() }}
            </div>
        @else
            <div class="empty-state">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                    <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                </svg>
                <h3>No hay categorías registradas</h3>
                <p>Comienza creando tu primera categoría</p>
                <a href="{{ route('admin.categorias.create') }}" class="btn btn-primary mt-3">Nueva Categoría</a>
            </div>
        @endif
    </div>
</div>
@endsection
