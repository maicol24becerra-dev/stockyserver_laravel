@extends('layouts.admin')

@section('title', 'Ficha de Caracterización - ' . $plato->nombre)

@section('content')
<div class="page-header">
    <h1>📋 Ficha de Caracterización del Producto</h1>
    <div>
        <a href="{{ route('admin.platos.edit', $plato) }}" class="btn btn-warning">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
            Editar
        </a>
        <a href="{{ route('admin.platos.index') }}" class="btn btn-secondary">Volver</a>
    </div>
</div>

<div class="row">
    {{-- Información Principal --}}
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">📌 Información General</h5>
            </div>
            <div class="card-body">
                @if($plato->imagen)
                    <div class="text-center mb-3">
                        <img src="{{ asset('storage/' . $plato->imagen) }}" alt="{{ $plato->nombre }}" style="max-width: 100%; max-height: 300px; border-radius: 10px;">
                    </div>
                @endif
                
                <table class="table table-borderless">
                    <tr>
                        <th width="180">Nombre:</th>
                        <td><strong style="font-size: 1.1rem;">{{ $plato->nombre }}</strong></td>
                    </tr>
                    <tr>
                        <th>Precio:</th>
                        <td><strong class="text-success" style="font-size: 1.2rem;">${{ number_format($plato->precio, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <th>Categoría:</th>
                        <td>
                            @if($plato->categoriaRelacion)
                                <span class="badge badge-info">{{ $plato->categoriaRelacion->icono }} {{ $plato->categoriaRelacion->nombre }}</span>
                            @elseif($plato->categoria)
                                <span class="badge badge-secondary">{{ $plato->categoria }}</span>
                            @else
                                <span class="text-muted">Sin categoría</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Disponibilidad:</th>
                        <td>
                            @if($plato->disponibilidad)
                                <span class="badge badge-success">✓ Disponible</span>
                            @else
                                <span class="badge badge-danger">✗ No disponible</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Descripción:</th>
                        <td>{{ $plato->descripcion ?? 'Sin descripción' }}</td>
                    </tr>
                    @if($plato->ingredientes_principales)
                        <tr>
                            <th>Ingredientes:</th>
                            <td>{{ $plato->ingredientes_principales }}</td>
                        </tr>
                    @endif
                    @if($plato->tiempo_preparacion)
                        <tr>
                            <th>Tiempo Prep.:</th>
                            <td>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle;">
                                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                                </svg>
                                {{ $plato->tiempo_preparacion }} minutos
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <th>Nivel Picante:</th>
                        <td>
                            @switch($plato->nivel_picante)
                                @case('ninguno')
                                    <span class="badge badge-secondary">Sin picante</span>
                                    @break
                                @case('bajo')
                                    <span class="badge badge-warning">🌶️ Bajo</span>
                                    @break
                                @case('medio')
                                    <span class="badge badge-warning">🌶️🌶️ Medio</span>
                                    @break
                                @case('alto')
                                    <span class="badge badge-danger">🌶️🌶️🌶️ Alto</span>
                                    @break
                            @endswitch
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Información Nutricional --}}
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">🥗 Información Nutricional</h5>
            </div>
            <div class="card-body">
                @if($plato->calorias || $plato->proteinas || $plato->carbohidratos || $plato->grasas)
                    <table class="table">
                        @if($plato->calorias)
                            <tr>
                                <th width="180">Calorías:</th>
                                <td><strong>{{ $plato->calorias }} kcal</strong></td>
                            </tr>
                        @endif
                        @if($plato->proteinas)
                            <tr>
                                <th>Proteínas:</th>
                                <td>{{ number_format($plato->proteinas, 1) }} g</td>
                            </tr>
                        @endif
                        @if($plato->carbohidratos)
                            <tr>
                                <th>Carbohidratos:</th>
                                <td>{{ number_format($plato->carbohidratos, 1) }} g</td>
                            </tr>
                        @endif
                        @if($plato->grasas)
                            <tr>
                                <th>Grasas:</th>
                                <td>{{ number_format($plato->grasas, 1) }} g</td>
                            </tr>
                        @endif
                    </table>
                @else
                    <p class="text-muted">No se ha registrado información nutricional para este plato.</p>
                @endif

                <hr>

                <h6>Características Dietéticas</h6>
                <div class="mt-3">
                    @if($plato->vegetariano)
                        <span class="badge badge-success" style="font-size: 0.9rem; padding: 8px 12px; margin: 4px;">
                            🥬 Vegetariano
                        </span>
                    @endif
                    @if($plato->vegano)
                        <span class="badge badge-success" style="font-size: 0.9rem; padding: 8px 12px; margin: 4px;">
                            🌱 Vegano
                        </span>
                    @endif
                    @if($plato->sin_gluten)
                        <span class="badge badge-info" style="font-size: 0.9rem; padding: 8px 12px; margin: 4px;">
                            🌾 Sin Gluten
                        </span>
                    @endif
                    @if(!$plato->vegetariano && !$plato->vegano && !$plato->sin_gluten)
                        <p class="text-muted mb-0">Sin características dietéticas especiales</p>
                    @endif
                </div>

                @if($plato->alergenos)
                    <hr>
                    <h6>⚠️ Alérgenos</h6>
                    <div class="alert alert-warning mb-0">
                        {{ $plato->alergenos }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Ingredientes/Recetas --}}
<div class="card">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">🧪 Receta e Ingredientes</h5>
    </div>
    <div class="card-body">
        @if($plato->recetas->count())
            <table class="table">
                <thead>
                    <tr>
                        <th>Materia Prima</th>
                        <th>Cantidad Requerida</th>
                        <th>Unidad</th>
                        <th>Stock Disponible</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($plato->recetas as $receta)
                        <tr>
                            <td>{{ $receta->materiaPrima->nombre ?? 'N/A' }}</td>
                            <td>{{ $receta->cantidad_requerida }}</td>
                            <td>{{ $receta->materiaPrima->unidad_medida ?? '' }}</td>
                            <td>
                                @if($receta->materiaPrima)
                                    @php
                                        $disponible = $receta->materiaPrima->cantidad_disponible;
                                        $minimo = $receta->materiaPrima->stock_minimo;
                                    @endphp
                                    <span class="badge badge-{{ $disponible <= $minimo ? 'danger' : 'success' }}">
                                        {{ number_format($disponible, 2) }} {{ $receta->materiaPrima->unidad_medida ?? '' }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-muted mb-0">No se han asignado ingredientes a este plato.</p>
            <a href="{{ route('admin.inventario.index') }}" class="btn btn-sm btn-primary mt-2">Gestionar Receta</a>
        @endif
    </div>
</div>
@endsection
