@extends('layouts.admin')

@section('title', 'Nueva Categoría')

@section('content')
<div class="page-header">
    <h1>Nueva Categoría</h1>
    <a href="{{ route('admin.categorias.index') }}" class="btn btn-secondary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
        </svg>
        Volver
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.categorias.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="nombre">Nombre <span class="text-danger">*</span></label>
                <input 
                    type="text" 
                    class="form-control @error('nombre') is-invalid @enderror" 
                    id="nombre" 
                    name="nombre" 
                    value="{{ old('nombre') }}" 
                    required
                    maxlength="100"
                    placeholder="Ej: Entradas, Platos Fuertes, Bebidas...">
                @error('nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea 
                    class="form-control @error('descripcion') is-invalid @enderror" 
                    id="descripcion" 
                    name="descripcion" 
                    rows="3"
                    maxlength="500"
                    placeholder="Descripción breve de esta categoría...">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="icono">Icono (Emoji)</label>
                <input 
                    type="text" 
                    class="form-control @error('icono') is-invalid @enderror" 
                    id="icono" 
                    name="icono" 
                    value="{{ old('icono') }}" 
                    maxlength="50"
                    placeholder="🍽️ (opcional)">
                <small class="form-text text-muted">Puedes usar un emoji para identificar visualmente la categoría</small>
                @error('icono')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input 
                        type="checkbox" 
                        class="custom-control-input" 
                        id="activo" 
                        name="activo" 
                        value="1" 
                        {{ old('activo', true) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="activo">
                        Categoría activa
                    </label>
                </div>
                <small class="form-text text-muted">Las categorías inactivas no se mostrarán en el sistema</small>
            </div>

            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Crear Categoría
                </button>
                <a href="{{ route('admin.categorias.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
