<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura #{{ str_pad($pedido->id_pedido, 6, '0', STR_PAD_LEFT) }} - El Cielo</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier Prime', 'Courier New', monospace;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .receipt-container {
            background: #ffffff;
            width: 100%;
            max-width: 420px;
            padding: 32px 28px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            position: relative;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 2px dashed #cbd5e1;
        }

        .receipt-header h1 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 6px;
            letter-spacing: 1px;
        }

        .receipt-header p {
            font-size: 0.88rem;
            color: #64748b;
            line-height: 1.5;
        }

        .receipt-info {
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 2px dashed #cbd5e1;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 0.88rem;
        }

        .info-row .label {
            color: #64748b;
            font-weight: 400;
        }

        .info-row .value {
            color: #1e293b;
            font-weight: 700;
            text-align: right;
        }

        .receipt-section-title {
            font-size: 0.82rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 14px;
            text-align: center;
        }

        .receipt-items {
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 2px dashed #cbd5e1;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 0.88rem;
            gap: 12px;
        }

        .item-name {
            flex: 1;
            color: #1e293b;
            font-weight: 700;
        }

        .item-qty {
            color: #64748b;
            white-space: nowrap;
        }

        .item-price {
            color: #1e293b;
            font-weight: 700;
            text-align: right;
            min-width: 90px;
        }

        .receipt-totals {
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 2px dashed #cbd5e1;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 0.88rem;
        }

        .total-row .label {
            color: #64748b;
        }

        .total-row .value {
            color: #1e293b;
            font-weight: 700;
        }

        .total-row.grand-total {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
        }

        .total-row.grand-total .label,
        .total-row.grand-total .value {
            font-size: 1.15rem;
            font-weight: 700;
            color: #1e293b;
        }

        .receipt-payment {
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 2px dashed #cbd5e1;
        }

        .payment-method {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.88rem;
        }

        .payment-method .label {
            color: #64748b;
        }

        .payment-method .value {
            color: #1e293b;
            font-weight: 700;
        }

        .payment-change {
            margin-top: 8px;
            font-size: 0.88rem;
            color: #64748b;
        }

        .receipt-footer {
            text-align: center;
            font-size: 0.82rem;
            color: #94a3b8;
            line-height: 1.6;
        }

        .receipt-footer p {
            margin-bottom: 4px;
        }

        .receipt-footer .thank-you {
            font-weight: 700;
            color: #64748b;
            margin-top: 12px;
            font-size: 0.9rem;
        }

        .receipt-footer .powered-by {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0;
            font-size: 0.78rem;
            color: #cbd5e1;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 32px;
        }

        .btn {
            flex: 1;
            padding: 14px 20px;
            border: none;
            border-radius: 8px;
            font-family: 'Courier Prime', monospace;
            font-size: 0.92rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-print {
            background: #1e293b;
            color: #ffffff;
        }

        .btn-print:hover {
            background: #0f172a;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .btn-back {
            background: #f1f5f9;
            color: #64748b;
        }

        .btn-back:hover {
            background: #e2e8f0;
            color: #475569;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .receipt-container {
                box-shadow: none;
                max-width: 100%;
            }

            .action-buttons {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <h1>EL CIELO</h1>
            <p>Centro Vacacional y Recreacional<br>Canoas, Colombia<br>Tel: 318 2626154</p>
        </div>

        <div class="receipt-info">
            <p class="receipt-section-title">FACTURA DE VENTA</p>
            <div class="info-row">
                <span class="label">Cliente:</span>
                <span class="value">{{ $pedido->cliente?->usuario?->nombre ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="label">FACTURA Nro.:</span>
                <span class="value">{{ str_pad($pedido->id_pedido, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="info-row">
                <span class="label">Usuario:</span>
                <span class="value">mesero</span>
            </div>
        </div>

        <div class="receipt-items">
            <p class="receipt-section-title">ARTÍCULO</p>
            @foreach($pedido->items as $item)
                @php
                    $subtotalItem = $item->cantidad * $item->precio_unitario;
                @endphp
                <div class="item-row">
                    <span class="item-name">{{ $item->plato?->nombre ?? 'Plato' }}</span>
                    <span class="item-qty">{{ $item->cantidad }} u.</span>
                    <span class="item-price">${{ number_format($subtotalItem, 3, '.', ',') }}</span>
                </div>
            @endforeach
        </div>

        <div class="receipt-totals">
            <div class="total-row">
                <span class="label">Subtotal</span>
                <span class="value">${{ number_format($subtotal, 2, '.', ',') }}</span>
            </div>
            <div class="total-row">
                <span class="label">Descuento</span>
                <span class="value">${{ number_format($descuento, 0, '', '') }}</span>
            </div>
            <div class="total-row grand-total">
                <span class="label">TOTAL</span>
                <span class="value">${{ number_format($total, 3, '.', ',') }}</span>
            </div>
        </div>

        <div class="receipt-payment">
            <p class="receipt-section-title">TIPO DE PAGO</p>
            <div class="payment-method">
                <span class="label">Efectivo</span>
                <span class="value">${{ number_format($total, 3, '.', ',') }}</span>
            </div>
            <div class="payment-change">
                <span class="label">Cambio</span>
                <span class="value" style="margin-left: 8px;">$0</span>
            </div>
        </div>

        <div class="receipt-footer">
            <p>¡Gracias por su visita!</p>
            <p class="thank-you">Vuelve pronto</p>
            <div class="powered-by">
                ------------------------------------<br>
                stockyserve Juan Herrera<br>
                Sistema StockyServe
            </div>
        </div>

        <div class="action-buttons">
            <button class="btn btn-print" onclick="window.print()">
                🖨 IMPRIMIR
            </button>
            <button class="btn btn-back" onclick="window.location='{{ route('mesero.dashboard', ['seccion' => 'activos']) }}'">
                ← VOLVER
            </button>
        </div>
    </div>
</body>
</html>
