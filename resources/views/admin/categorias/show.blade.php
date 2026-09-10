@extends('layouts.admin')

@section('title', 'Detalle Categoría')

@section('content')
<div class="page-header">
    <h1>Categoría: {{ $categoria->nombre }}</h1>
    <div>
        <a href="{{ route('admin.categorias.edit', $categoria) }}" class="btn btn-warning">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
            Editar
        </a>
        <a href="{{ route('admin.categorias.index') }}" class="btn btn-secondary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
            </svg>
            Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Información de la Categoría</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">ID:</th>
                        <td>{{ $categoria->id_categoria }}</td>
                    </tr>
                    <tr>
                        <th>Nombre:</th>
                        <td><strong>{{ $categoria->nombre }}</strong></td>
                    </tr>
                    <tr>
                        <th>Descripción:</th>
                        <td>{{ $categoria->descripcion ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Icono:</th>
                        <td>
                            @if($categoria->icono)
                                <span style="font-size: 2rem;">{{ $categoria->icono }}</span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Estado:</th>
                        <td>
                            @if($categoria->activo)
                                <span class="badge badge-success">Activa</span>
                            @else
                                <span class="badge badge-secondary">Inactiva</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Total Platos:</th>
                        <td><span class="badge badge-info">{{ $categoria->platos_count }}</span></td>
                    </tr>
                    <tr>
                        <th>Creada:</th>
                        <td>{{ $categoria->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Actualizada:</th>
                        <td>{{ $categoria->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Platos en esta Categoría</h5>
            </div>
            <div class="card-body">
                @if($platos->count())
                    <div class="list-group">
                        @foreach($platos as $plato)
                            <a href="{{ route('admin.platos.show', $plato) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <h6 class="mb-0">{{ $plato->nombre }}</h6>
                                    <span class="badge badge-primary">${{ number_format($plato->precio, 2) }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div class="mt-3">
                        {{ $platos->links() }}
                    </div>
                @else
                    <p class="text-muted mb-0">No hay platos asignados a esta categoría.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
