@extends('layouts.admin')

@section('title', 'Panel de Administrador - El Cielo')
@section('page_title', 'Gestión de Platos')

@section('content')

<div class="admin-card">
    <div class="admin-card-header">
        <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800;">Catálogo de Platos</h3>
        <a href="{{ route('admin.platos.create') }}" class="btn-aplicar" style="text-decoration: none;">
            + Nuevo Plato
        </a>
    </div>

    @if($platos->isEmpty())
        <p style="text-align: center; color: #94a3b8; padding: 40px 0; font-weight: 600;">No hay platos registrados.</p>
    @else
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Categoría</th>
                        <th>Disponibilidad</th>
                        <th style="text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($platos as $plato)
                        <tr>
                            <td><strong>#{{ $plato->id_plato }}</strong></td>
                            <td><strong>{{ $plato->nombre }}</strong></td>
                            <td style="font-weight: 800; color: #009640;">${{ number_format($plato->precio, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge" style="background-color: #e0f2fe; color: #0369a1;">
                                    {{ $plato->categoria ?? 'Sin categoría' }}
                                </span>
                            </td>
                            <td>
                                @if($plato->disponibilidad > 0)
                                    <span class="badge badge-success">Disponible</span>
                                @else
                                    <span class="badge badge-danger">No disponible</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <a href="{{ route('admin.platos.show', $plato) }}" style="color: #0d9488; font-weight: 700; text-decoration: none; margin-right: 10px;">Ver</a>
                                <a href="{{ route('admin.platos.edit', $plato) }}" style="color: #2563eb; font-weight: 700; text-decoration: none; margin-right: 10px;">Editar</a>
                                <form method="POST" action="{{ route('admin.platos.destroy', $plato) }}" onsubmit="return confirm('¿Seguro que deseas eliminar este plato?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #ef4444; font-weight: 700; cursor: pointer; font-family: inherit;">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $platos->links() }}
        </div>
    @endif
</div>

@endsection
