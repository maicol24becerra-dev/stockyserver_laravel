<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas — El Cielo</title>
    <link rel="icon" href="{{ asset('images/logo-circle.png') }}?v={{ time() }}" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #525659;
            margin: 0;
            padding: 0;
            color: #1e293b;
        }

        .no-print-bar {
            position: sticky;
            top: 0;
            background: #1e293b;
            color: white;
            padding: 12px 24px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 16px;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .btn-print {
            background-color: #009640;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: inherit;
        }

        .btn-print:hover {
            background-color: #007a34;
        }

        .btn-close {
            background-color: #475569;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            text-decoration: none;
            font-family: inherit;
        }

        .paper-page {
            width: 210mm;
            min-height: 297mm;
            background: #ffffff;
            margin: 24px auto;
            padding: 36px 44px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.3);
            box-sizing: border-box;
        }

        .doc-header {
            text-align: center;
            margin-bottom: 24px;
            border-bottom: 2px solid #009640;
            padding-bottom: 16px;
        }

        .doc-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #063b28;
            margin: 0 0 6px 0;
        }

        .doc-meta {
            font-size: 0.8rem;
            color: #64748b;
            margin: 0;
            font-weight: 500;
        }

        .section-title {
            font-size: 1.05rem;
            font-weight: 800;
            color: #063b28;
            margin: 20px 0 12px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-accent {
            width: 4px;
            height: 18px;
            background-color: #009640;
            display: inline-block;
            border-radius: 2px;
        }

        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .kpi-box {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px;
            text-align: center;
            background: #fafafa;
        }

        .kpi-box-label {
            font-size: 0.65rem;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .kpi-box-val {
            font-size: 1.25rem;
            font-weight: 900;
            color: #009640;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8rem;
            margin-bottom: 20px;
        }

        th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.7rem;
            padding: 8px 10px;
            border-bottom: 2px solid #e2e8f0;
            text-align: left;
        }

        td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
        }

        .doc-footer {
            margin-top: 40px;
            border-top: 1px solid #e2e8f0;
            padding-top: 16px;
            text-align: center;
            font-size: 0.75rem;
            color: #94a3b8;
            font-weight: 600;
        }

        @media print {
            .no-print-bar { display: none !important; }
            body { background: white !important; }
            .paper-page {
                width: 100% !important;
                margin: 0 !important;
                box-shadow: none !important;
                padding: 0 !important;
            }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <button class="btn-print" onclick="window.print()">
            🖨️ Imprimir / Guardar como PDF
        </button>
        <a href="{{ route('admin.reportes.ventas', request()->query()) }}" class="btn-close">
            Cerrar
        </a>
    </div>

    <div class="paper-page">
        <div class="doc-header">
            <h1 class="doc-title">El Cielo — Reporte de Ventas</h1>
            <p class="doc-meta">
                Generado el {{ now()->format('d/m/Y H:i') }} por {{ auth()->user()->nombre ?? 'admin' }} | Período: <strong>{{ ucfirst(str_replace('_', ' ', $periodo)) }}</strong>
            </p>
        </div>

        <div class="section-title">
            <span class="section-accent"></span> Resumen General
        </div>

        <div class="kpi-grid">
            <div class="kpi-box">
                <div class="kpi-box-label">TOTAL VENTAS</div>
                <div class="kpi-box-val">${{ number_format($totalVentas, 2) }}</div>
            </div>
            <div class="kpi-box">
                <div class="kpi-box-label">PEDIDOS PAGADOS</div>
                <div class="kpi-box-val" style="color: #8b5cf6;">{{ $pedidosPagados }}</div>
            </div>
            <div class="kpi-box">
                <div class="kpi-box-label">CLIENTES ATENDIDOS</div>
                <div class="kpi-box-val" style="color: #f87171;">{{ $clientesAtendidos }}</div>
            </div>
            <div class="kpi-box">
                <div class="kpi-box-label">TICKET PROMEDIO</div>
                <div class="kpi-box-val" style="color: #0d9488;">${{ number_format($ticketPromedio, 2) }}</div>
            </div>
        </div>

        <div class="section-title">
            <span class="section-accent"></span> Ventas por Método de Pago
        </div>
        <table>
            <thead>
                <tr>
                    <th>Método</th>
                    <th>Pedidos</th>
                    <th>Total Recaudado</th>
                    <th>Participación</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ventasPorMetodo as $metodo)
                    <tr>
                        <td><strong style="text-transform: capitalize;">{{ $metodo->metodo_pago }}</strong></td>
                        <td>{{ $metodo->total_pedidos }}</td>
                        <td>${{ number_format($metodo->total_monto, 2) }}</td>
                        <td>{{ $metodo->participacion }}%</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #94a3b8;">Sin datos registrados en este período</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="section-title">
            <span class="section-accent"></span> Platillos Más Vendidos
        </div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Platillo</th>
                    <th>Categoría</th>
                    <th>Unidades Vendidas</th>
                    <th>Ingresos Generados</th>
                </tr>
            </thead>
            <tbody>
                @forelse($platillosMasVendidos as $idx => $item)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td><strong>{{ $item->nombre }}</strong></td>
                        <td>{{ $item->categoria_nombre ?? $item->plato_categoria_str ?? '-' }}</td>
                        <td>{{ $item->total_unidades }}</td>
                        <td><strong>${{ number_format($item->total_ingresos, 2) }}</strong></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #94a3b8;">Sin datos registrados en este período</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="section-title">
            <span class="section-accent"></span> Detalle de Ventas por Período
        </div>
        <table>
            <thead>
                <tr>
                    <th>Fecha / Período</th>
                    <th>Pedidos</th>
                    <th style="text-align: right;">Total Ventas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pedidos as $pedido)
                    <tr>
                        <td>{{ $pedido->fecha ? $pedido->fecha->format('d/m/Y') : '-' }}</td>
                        <td>#{{ $pedido->id_pedido }} ({{ ucfirst($pedido->estado) }})</td>
                        <td style="text-align: right;">
                            <strong>
                                ${{ number_format($pedido->pago ? $pedido->pago->monto_pagado : $pedido->items->sum(fn($i) => $i->cantidad * $i->precio_unitario), 2) }}
                            </strong>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: #94a3b8;">Sin ventas registradas en este período</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="doc-footer">
            StockYServe — Sistema de Gestión El Cielo | Reporte generado automáticamente
        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', function() {
            // Auto open print dialog when PDF view opens
            setTimeout(function() {
                window.print();
            }, 600);
        });
    </script>
</body>
</html>
