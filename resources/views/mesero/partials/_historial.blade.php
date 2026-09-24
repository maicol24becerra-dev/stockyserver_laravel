{{-- SECCIÓN HISTORIAL --}}
<section class="content-section">
    <div class="panel">
        <div class="panel-heading">
            <h2>Historial de Pedidos</h2>
        </div>
        <div class="history-table">
            <div class="history-head">
                <span>#</span>
                <span>FECHA</span>
                <span>CLIENTE</span>
                <span>ESTADO</span>
            </div>
            @forelse($pedidos as $pedido)
                @php
                    $estado = strtolower(trim($pedido->estado));
                    $estadoClass = str_replace(' ', '-', $estado);
                @endphp
                <div class="history-row">
                    <b>#{{ $pedido->id_pedido }}</b>
                    <span>{{ $pedido->fecha?->format('d/m/Y H:i') }}</span>
                    <span>{{ $pedido->cliente?->usuario?->nombre ?? $pedido->usuario?->nombre ?? 'mesero' }}</span>
                    <div>
                        <span class="status {{ $estadoClass }}">{{ ucfirst($estado) }}</span>
                    </div>
                </div>
            @empty
                <div class="empty"><p>No hay pedidos registrados.</p></div>
            @endforelse
        </div>
    </div>
</section>
