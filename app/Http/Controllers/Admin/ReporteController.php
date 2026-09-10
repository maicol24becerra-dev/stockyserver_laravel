<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Pago;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    /**
     * Reporte de ventas con filtros (HU-04)
     */
    public function ventas(Request $request)
    {
        $query = Pedido::with(['items.plato', 'cliente.usuario', 'usuario', 'pago'])
            ->whereIn('estado', ['entregado', 'cancelado', 'listo', 'en preparación', 'pendiente']);

        // Filtro por Período
        $periodo = $request->get('periodo', 'este_mes');
        if ($periodo === 'hoy') {
            $query->whereDate('fecha', today());
        } elseif ($periodo === 'ayer') {
            $query->whereDate('fecha', today()->subDay());
        } elseif ($periodo === 'esta_semana') {
            $query->whereBetween('fecha', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($periodo === 'este_mes' || $periodo === 'mes_actual') {
            $query->whereMonth('fecha', now()->month)->whereYear('fecha', now()->year);
        } elseif ($periodo === 'este_ano') {
            $query->whereYear('fecha', now()->year);
        }

        // Filtro por fecha desde / hasta
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtro por mesero
        if ($request->filled('id_usuario')) {
            $query->where('id_usuario', $request->id_usuario);
        }

        // Filtro por método de pago
        if ($request->filled('metodo_pago') && $request->metodo_pago !== 'Todos') {
            $query->whereHas('pago', function ($q) use ($request) {
                $q->where('metodo_pago', $request->metodo_pago);
            });
        }

        // Filtro por categoría de plato
        if ($request->filled('categoria') && $request->categoria !== 'Todas') {
            $query->whereHas('items.plato', function ($q) use ($request) {
                $q->where('categoria', $request->categoria)
                  ->orWhere('id_categoria', $request->categoria);
            });
        }

        // Filtro por platillo específico
        if ($request->filled('id_plato') && $request->id_plato !== 'Todos') {
            $query->whereHas('items', function ($q) use ($request) {
                $q->where('id_plato', $request->id_plato);
            });
        }

        $allMatchingPedidos = (clone $query)->get();

        $totalVentas = $allMatchingPedidos->sum(function ($pedido) {
            if ($pedido->pago && $pedido->pago->monto_pagado > 0) {
                return $pedido->pago->monto_pagado;
            }
            return $pedido->items->sum(fn($item) => $item->cantidad * $item->precio_unitario);
        });

        $totalPedidos = $allMatchingPedidos->count();
        $pedidosPagados = $allMatchingPedidos->filter(fn($p) => $p->pago !== null || $p->estado === 'entregado')->count();
        $pedidosEntregados = $allMatchingPedidos->where('estado', 'entregado')->count();
        $pedidosCancelados = $allMatchingPedidos->where('estado', 'cancelado')->count();

        // Clientes atendidos únicos
        $clientesAtendidos = $allMatchingPedidos->pluck('id_cliente')->filter()->unique()->count();

        // Ticket promedio
        $ticketPromedio = $pedidosPagados > 0 ? ($totalVentas / $pedidosPagados) : ($totalPedidos > 0 ? $totalVentas / $totalPedidos : 0);

        $pedidos = $query->orderByDesc('fecha')->paginate(15);

        // Meseros
        $meseros = User::whereHas('role', function ($q) {
            $q->whereIn('nombre', ['Mesero', 'Administrador']);
        })->orderBy('nombre')->get();

        // Categorías y Platos para selects
        $categorias = \App\Models\Categoria::orderBy('nombre')->get();
        $platos = \App\Models\Plato::orderBy('nombre')->get();

        // Data para el gráfico (Evolución de ventas por fecha)
        $chartQuery = clone $query;
        $ventasGrouped = $allMatchingPedidos
            ->groupBy(fn($p) => $p->fecha ? $p->fecha->format('d/m') : now()->format('d/m'));

        $chartLabels = [];
        $chartVentasData = [];
        $chartPedidosData = [];

        if ($ventasGrouped->isNotEmpty()) {
            foreach ($ventasGrouped as $fechaFormatted => $pedidosGrupo) {
                $chartLabels[] = $fechaFormatted;
                $montoGrupo = $pedidosGrupo->sum(function ($p) {
                    return $p->pago ? $p->pago->monto_pagado : $p->items->sum(fn($i) => $i->cantidad * $i->precio_unitario);
                });
                $chartVentasData[] = round($montoGrupo, 2);
                $chartPedidosData[] = $pedidosGrupo->count();
            }
        } else {
            // Ejemplo por defecto si no hay datos
            $chartLabels = [now()->format('d/m')];
            $chartVentasData = [0];
            $chartPedidosData = [0];
        }

        // Ranking de Platillos con imágenes y categorías
        $rankingPlatillos = DB::table('item_pedido')
            ->join('plato', 'item_pedido.id_plato', '=', 'plato.id_plato')
            ->join('pedido', 'item_pedido.id_pedido', '=', 'pedido.id_pedido')
            ->leftJoin('categoria', 'plato.id_categoria', '=', 'categoria.id_categoria')
            ->whereIn('pedido.estado', ['entregado', 'listo', 'en preparación'])
            ->select(
                'plato.id_plato',
                'plato.nombre',
                'plato.imagen',
                'plato.categoria as plato_categoria_str',
                'categoria.nombre as categoria_nombre',
                DB::raw('SUM(item_pedido.cantidad) as total_unidades'),
                DB::raw('SUM(item_pedido.cantidad * item_pedido.precio_unitario) as total_ingresos')
            )
            ->groupBy('plato.id_plato', 'plato.nombre', 'plato.imagen', 'plato.categoria', 'categoria.nombre')
            ->orderByDesc('total_ingresos')
            ->limit(10)
            ->get();

        // Ventas por método de pago con porcentaje
        $ventasPorMetodo = DB::table('pago')
            ->join('pedido', 'pago.id_pedido', '=', 'pedido.id_pedido')
            ->select(
                'pago.metodo_pago',
                DB::raw('COUNT(*) as total_pedidos'),
                DB::raw('SUM(pago.monto_pagado) as total_monto')
            )
            ->groupBy('pago.metodo_pago')
            ->get()
            ->map(function ($item) use ($totalVentas) {
                $item->porcentaje = $totalVentas > 0 ? round(($item->total_monto / $totalVentas) * 100, 1) : 0;
                return $item;
            });

        return view('admin.reportes.ventas', compact(
            'pedidos',
            'totalVentas',
            'totalPedidos',
            'pedidosPagados',
            'pedidosEntregados',
            'pedidosCancelados',
            'clientesAtendidos',
            'ticketPromedio',
            'meseros',
            'categorias',
            'platos',
            'chartLabels',
            'chartVentasData',
            'chartPedidosData',
            'rankingPlatillos',
            'ventasPorMetodo'
        ));
    }

    /**
     * Vista de reporte imprimible / PDF (HU-04)
     */
    public function exportarPdf(Request $request)
    {
        $query = Pedido::with(['items.plato', 'cliente.usuario', 'usuario', 'pago'])
            ->whereIn('estado', ['entregado', 'cancelado', 'listo', 'en preparación']);

        // Filtro por Período
        $periodo = $request->get('periodo', 'este_mes');
        if ($periodo === 'hoy') {
            $query->whereDate('fecha', today());
        } elseif ($periodo === 'ayer') {
            $query->whereDate('fecha', today()->subDay());
        } elseif ($periodo === 'esta_semana') {
            $query->whereBetween('fecha', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($periodo === 'este_mes' || $periodo === 'mes_actual') {
            $query->whereMonth('fecha', now()->month)->whereYear('fecha', now()->year);
        } elseif ($periodo === 'este_ano') {
            $query->whereYear('fecha', now()->year);
        }

        $pedidos = $query->orderByDesc('fecha')->get();

        $totalVentas = $pedidos->sum(function ($pedido) {
            if ($pedido->pago && $pedido->pago->monto_pagado > 0) {
                return $pedido->pago->monto_pagado;
            }
            return $pedido->items->sum(fn($item) => $item->cantidad * $item->precio_unitario);
        });

        $totalPedidos = $pedidos->count();
        $pedidosPagados = $pedidos->filter(fn($p) => $p->pago !== null || $p->estado === 'entregado')->count();
        $clientesAtendidos = $pedidos->pluck('id_cliente')->filter()->unique()->count();
        $ticketPromedio = $pedidosPagados > 0 ? ($totalVentas / $pedidosPagados) : 0;

        // Platillos más vendidos
        $platillosMasVendidos = DB::table('item_pedido')
            ->join('plato', 'item_pedido.id_plato', '=', 'plato.id_plato')
            ->join('pedido', 'item_pedido.id_pedido', '=', 'pedido.id_pedido')
            ->leftJoin('categoria', 'plato.id_categoria', '=', 'categoria.id_categoria')
            ->whereIn('pedido.estado', ['entregado', 'listo', 'en preparación'])
            ->select(
                'plato.nombre',
                'plato.categoria as plato_categoria_str',
                'categoria.nombre as categoria_nombre',
                DB::raw('SUM(item_pedido.cantidad) as total_unidades'),
                DB::raw('SUM(item_pedido.cantidad * item_pedido.precio_unitario) as total_ingresos')
            )
            ->groupBy('plato.id_plato', 'plato.nombre', 'plato.categoria', 'categoria.nombre')
            ->orderByDesc('total_ingresos')
            ->limit(10)
            ->get();

        // Ventas por Método de Pago
        $ventasPorMetodo = DB::table('pago')
            ->join('pedido', 'pago.id_pedido', '=', 'pedido.id_pedido')
            ->select(
                'pago.metodo_pago',
                DB::raw('COUNT(*) as total_pedidos'),
                DB::raw('SUM(pago.monto_pagado) as total_monto')
            )
            ->groupBy('pago.metodo_pago')
            ->get()
            ->map(function ($item) use ($totalVentas) {
                $item->participacion = $totalVentas > 0 ? round(($item->total_monto / $totalVentas) * 100, 1) : 0;
                return $item;
            });

        return view('admin.reportes.pdf', compact(
            'periodo',
            'totalVentas',
            'totalPedidos',
            'pedidosPagados',
            'clientesAtendidos',
            'ticketPromedio',
            'ventasPorMetodo',
            'platillosMasVendidos',
            'pedidos'
        ));
    }

    /**
     * Exportar reporte a CSV
     */
    public function exportarVentas(Request $request)
    {
        $query = Pedido::with(['items.plato', 'cliente.usuario', 'usuario', 'pago'])
            ->whereIn('estado', ['entregado', 'cancelado']);

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('id_usuario')) {
            $query->where('id_usuario', $request->id_usuario);
        }

        $pedidos = $query->orderByDesc('fecha')->get();

        $filename = 'reporte_ventas_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($pedidos) {
            $file = fopen('php://output', 'w');

            // Encabezados
            fputcsv($file, [
                'ID Pedido',
                'Fecha',
                'Cliente',
                'Mesero',
                'Estado',
                'Total',
                'Método Pago',
                'Monto Pagado'
            ]);

            // Datos
            foreach ($pedidos as $pedido) {
                $total = $pedido->items->sum(fn($item) => $item->cantidad * $item->precio_unitario);

                fputcsv($file, [
                    $pedido->id_pedido,
                    $pedido->fecha?->format('d/m/Y H:i'),
                    $pedido->cliente?->usuario?->nombre ?? 'Sin cliente',
                    $pedido->usuario?->nombre ?? '-',
                    ucfirst($pedido->estado),
                    number_format($total, 2, '.', ''),
                    $pedido->pago?->metodo_pago ?? '-',
                    $pedido->pago ? number_format($pedido->pago->monto_pagado, 2, '.', '') : '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Panel de supervisión en tiempo real (HU-08)
     */
    public function supervision()
    {
        // Pedidos activos por estado
        $pedidosPorEstado = Pedido::whereNotIn('estado', ['entregado', 'cancelado'])
            ->selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado');

        // Pedidos de hoy por hora
        $pedidosHoy = Pedido::whereDate('fecha', today())
            ->selectRaw('HOUR(fecha) as hora, COUNT(*) as total')
            ->groupBy('hora')
            ->pluck('total', 'hora');

        // Meseros activos con pedidos pendientes
        $meserosActivos = User::whereHas('role', function ($q) {
                $q->whereIn('nombre', ['Mesero', 'Administrador']);
            })
            ->withCount(['pedidosComoUsuario as pedidos_pendientes' => function ($q) {
                $q->whereIn('estado', ['pendiente', 'en preparación', 'listo']);
            }])
            ->having('pedidos_pendientes', '>', 0)
            ->get();

        // Tiempo promedio de preparación (últimos 10 pedidos entregados)
        $tiemposPreparacion = Pedido::where('estado', 'entregado')
            ->whereNotNull('fecha')
            ->orderByDesc('fecha')
            ->limit(10)
            ->get()
            ->map(function ($pedido) {
                // Simulamos tiempo de preparación basado en items
                $totalItems = $pedido->items->sum('cantidad');
                return $totalItems * 8; // 8 minutos promedio por item
            });

        $tiempoPromedio = $tiemposPreparacion->avg() ?? 0;

        // Platos más pedidos hoy
        $platosMasPedidos = DB::table('item_pedido')
            ->join('plato', 'item_pedido.id_plato', '=', 'plato.id_plato')
            ->join('pedido', 'item_pedido.id_pedido', '=', 'pedido.id_pedido')
            ->whereDate('pedido.fecha', today())
            ->select(
                'plato.nombre',
                DB::raw('SUM(item_pedido.cantidad) as total_pedidos')
            )
            ->groupBy('plato.id_plato', 'plato.nombre')
            ->orderByDesc('total_pedidos')
            ->limit(5)
            ->get();

        // Stock crítico
        $stockCritico = \App\Models\MateriaPrima::whereColumn('cantidad_disponible', '<=', 'stock_minimo')
            ->count();

        // Últimos 15 pedidos en tiempo real
        $pedidosEnTiempoReal = Pedido::with(['cliente.usuario', 'usuario', 'items.plato'])
            ->whereNotIn('estado', ['cancelado'])
            ->orderByDesc('fecha')
            ->limit(15)
            ->get();

        return view('admin.supervision', compact(
            'pedidosPorEstado',
            'pedidosHoy',
            'meserosActivos',
            'tiempoPromedio',
            'platosMasPedidos',
            'stockCritico',
            'pedidosEnTiempoReal'
        ));
    }
}
