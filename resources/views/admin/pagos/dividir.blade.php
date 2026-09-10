@extends('layouts.admin')

@section('title', 'Dividir Cuenta')

@section('content')
<div class="page-header">
    <h1>💳 Dividir Cuenta - Pedido #{{ $pedido->id_pedido }}</h1>
    <a href="{{ route('mesero.dashboard', ['seccion' => 'activos']) }}" class="btn btn-secondary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
        </svg>
        Volver
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <p class="mb-0">{{ $error }}</p>
        @endforeach
    </div>
@endif

<div class="row">
    {{-- Información del pedido --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">📋 Resumen del Pedido</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th>Cliente:</th>
                        <td>{{ $pedido->cliente?->usuario?->nombre ?? 'Sin cliente' }}</td>
                    </tr>
                    <tr>
                        <th>Fecha:</th>
                        <td>{{ $pedido->fecha?->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Estado:</th>
                        <td><span class="badge badge-success">{{ ucfirst($pedido->estado) }}</span></td>
                    </tr>
                </table>

                <h6 class="mt-3">Items del Pedido:</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <tbody>
                            @foreach($pedido->items as $item)
                                <tr>
                                    <td>{{ $item->cantidad }}x</td>
                                    <td>{{ $item->plato?->nombre ?? 'Plato no encontrado' }}</td>
                                    <td class="text-right">${{ number_format($item->cantidad * $item->precio_unitario, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-info">
                                <th colspan="2">TOTAL:</th>
                                <th class="text-right">${{ number_format($total, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Formulario de división --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">💰 Dividir Pago</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.pedidos.procesar-division', $pedido) }}" method="POST">
                    @csrf

                    <div class="alert alert-info">
                        <strong>Total a dividir: ${{ number_format($total, 2) }}</strong><br>
                        <small>La suma de todas las divisiones debe ser exactamente igual al total del pedido.</small>
                    </div>

                    <div id="divisiones-container">
                        {{-- División 1 (default) --}}
                        <div class="division-item card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">División 1</h6>
                                    <button type="button" class="btn btn-sm btn-danger remove-division" onclick="removeDivision(this)" style="display: none;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                                        </svg>
                                    </button>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Descripción:</label>
                                            <input type="text" name="divisiones[0][descripcion]" class="form-control" 
                                                   placeholder="Ej: Cliente 1, Mesa 5..." required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Monto:</label>
                                            <input type="number" name="divisiones[0][monto]" class="form-control monto-input" 
                                                   step="0.01" min="0.01" max="{{ $total }}" required onchange="calcularTotal()">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Método de Pago:</label>
                                            <select name="divisiones[0][metodo_pago]" class="form-control" required>
                                                <option value="efectivo">💵 Efectivo</option>
                                                <option value="tarjeta">💳 Tarjeta</option>
                                                <option value="transferencia">🏦 Transferencia</option>
                                                <option value="nequi">📱 Nequi</option>
                                                <option value="daviplata">📲 Daviplata</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <button type="button" class="btn btn-success" onclick="addDivision()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Agregar División
                        </button>
                        
                        <div class="text-right">
                            <strong>Suma actual: $<span id="suma-total">0.00</span></strong><br>
                            <small>Restante: $<span id="restante">{{ number_format($total, 2) }}</span></small>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Procesar División de Cuenta
                        </button>
                        <a href="{{ route('mesero.dashboard', ['seccion' => 'activos']) }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let divisionCounter = 1;
const totalPedido = {{ $total }};

function addDivision() {
    const container = document.getElementById('divisiones-container');
    const newDivision = document.createElement('div');
    newDivision.className = 'division-item card mb-3';
    newDivision.innerHTML = `
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">División ${divisionCounter + 1}</h6>
                <button type="button" class="btn btn-sm btn-danger remove-division" onclick="removeDivision(this)">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Descripción:</label>
                        <input type="text" name="divisiones[${divisionCounter}][descripcion]" class="form-control" 
                               placeholder="Ej: Cliente ${divisionCounter + 1}, Mesa ${divisionCounter + 1}..." required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Monto:</label>
                        <input type="number" name="divisiones[${divisionCounter}][monto]" class="form-control monto-input" 
                               step="0.01" min="0.01" max="${totalPedido}" required onchange="calcularTotal()">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Método de Pago:</label>
                        <select name="divisiones[${divisionCounter}][metodo_pago]" class="form-control" required>
                            <option value="efectivo">💵 Efectivo</option>
                            <option value="tarjeta">💳 Tarjeta</option>
                            <option value="transferencia">🏦 Transferencia</option>
                            <option value="nequi">📱 Nequi</option>
                            <option value="daviplata">📲 Daviplata</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.appendChild(newDivision);
    divisionCounter++;
    
    // Mostrar botones de eliminar si hay más de una división
    updateRemoveButtons();
}

function removeDivision(button) {
    button.closest('.division-item').remove();
    calcularTotal();
    updateRemoveButtons();
}

function updateRemoveButtons() {
    const divisions = document.querySelectorAll('.division-item');
    const removeButtons = document.querySelectorAll('.remove-division');
    
    removeButtons.forEach(btn => {
        btn.style.display = divisions.length > 1 ? 'block' : 'none';
    });
}

function calcularTotal() {
    const montos = document.querySelectorAll('.monto-input');
    let suma = 0;
    
    montos.forEach(input => {
        const valor = parseFloat(input.value) || 0;
        suma += valor;
    });
    
    document.getElementById('suma-total').textContent = suma.toFixed(2);
    
    const restante = totalPedido - suma;
    document.getElementById('restante').textContent = restante.toFixed(2);
    
    // Cambiar color según si la suma es correcta
    const sumaElement = document.getElementById('suma-total');
    const restanteElement = document.getElementById('restante');
    
    if (Math.abs(restante) < 0.01) {
        sumaElement.style.color = 'green';
        restanteElement.style.color = 'green';
    } else if (restante < 0) {
        sumaElement.style.color = 'red';
        restanteElement.style.color = 'red';
    } else {
        sumaElement.style.color = 'orange';
        restanteElement.style.color = 'orange';
    }
}

// Calcular total inicial
calcularTotal();
</script>
@endsection