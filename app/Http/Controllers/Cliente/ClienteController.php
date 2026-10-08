<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ClienteController extends Controller
{
    /**
     * Dashboard del cliente.
     */
    public function dashboard(): View
    {
        $usuario = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Buscar el cliente relacionado con el usuario
        |--------------------------------------------------------------------------
        */

        $cliente = $usuario->cliente;

        /*
        |--------------------------------------------------------------------------
        | Pedidos del cliente
        |--------------------------------------------------------------------------
        */

        $pedidos = collect();

        if ($cliente) {
            $pedidos = Pedido::with([
                'items.plato',
                'pago',
            ])
                ->where('id_cliente', $cliente->id_cliente)
                ->orderByDesc('fecha')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Estadísticas
        |--------------------------------------------------------------------------
        */

        $totalPedidos = $pedidos->count();

        $pedidosPendientes = $pedidos->where(
            'estado',
            'pendiente'
        )->count();

        $pedidosEnPreparacion = $pedidos->where(
            'estado',
            'en preparación'
        )->count();

        $pedidosListos = $pedidos->where(
            'estado',
            'listo'
        )->count();

        $pedidosEntregados = $pedidos->where(
            'estado',
            'entregado'
        )->count();

        return view('cliente.dashboard', compact(
            'usuario',
            'cliente',
            'pedidos',
            'totalPedidos',
            'pedidosPendientes',
            'pedidosEnPreparacion',
            'pedidosListos',
            'pedidosEntregados'
        ));
    }

    /**
     * Menú digital con buscador y filtros (HU-11)
     */
    public function menu(\Illuminate\Http\Request $request): View
    {
        $query = \App\Models\Plato::where('disponibilidad', '>', 0);

        // Filtro por búsqueda de texto
        if ($request->filled('buscar')) {
            $busqueda = $request->buscar;
            $query->where(function ($q) use ($busqueda) {
                $q->where('nombre', 'like', "%{$busqueda}%")
                  ->orWhere('descripcion', 'like', "%{$busqueda}%");
            });
        }

        // Filtro por categoría
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        // Filtro por rango de precio
        if ($request->filled('precio_min')) {
            $query->where('precio', '>=', $request->precio_min);
        }
        if ($request->filled('precio_max')) {
            $query->where('precio', '<=', $request->precio_max);
        }

        // Ordenamiento
        $ordenar = $request->get('ordenar', 'nombre');
        switch ($ordenar) {
            case 'precio_asc':
                $query->orderBy('precio', 'asc');
                break;
            case 'precio_desc':
                $query->orderBy('precio', 'desc');
                break;
            default:
                $query->orderBy('nombre', 'asc');
        }

        $platos = $query->paginate(12)->appends($request->query());

        // Obtener categorías únicas desde los platos disponibles
        $categorias = \App\Models\Plato::where('disponibilidad', '>', 0)
            ->distinct('categoria')
            ->pluck('categoria')
            ->filter()
            ->sort()
            ->values();

        // Rangos de precio para el filtro
        $precioMin = \App\Models\Plato::where('disponibilidad', '>', 0)->min('precio');
        $precioMax = \App\Models\Plato::where('disponibilidad', '>', 0)->max('precio');

        return view('cliente.menu', compact(
            'platos',
            'categorias',
            'precioMin',
            'precioMax'
        ));
    }

    /**
     * Repetir pedido anterior (HU-13)
     */
    public function repetirPedido(Pedido $pedido)
    {
        $usuario = Auth::user();
        $cliente = $usuario->cliente;

        if (!$cliente) {
            return back()->withErrors(['error' => 'No se encontró el perfil de cliente.']);
        }

        // Verificar que el pedido pertenezca al cliente
        if ($pedido->id_cliente !== $cliente->id_cliente) {
            abort(403, 'No tienes permiso para repetir este pedido.');
        }

        // Verificar que el pedido esté completado
        if (strtolower($pedido->estado) !== 'entregado') {
            return back()->withErrors(['error' => 'Solo se pueden repetir pedidos entregados.']);
        }

        // Cargar items del pedido original
        $pedido->load('items.plato');

        // Verificar disponibilidad de platos
        $platosNoDisponibles = [];
        foreach ($pedido->items as $item) {
            if (!$item->plato || !$item->plato->disponibilidad) {
                $platosNoDisponibles[] = $item->plato?->nombre ?? 'Plato eliminado';
            }
        }

        if (!empty($platosNoDisponibles)) {
            return back()->withErrors([
                'error' => 'Los siguientes platos ya no están disponibles: ' . implode(', ', $platosNoDisponibles)
            ]);
        }

        // Crear nuevo pedido
        $nuevoPedido = Pedido::create([
            'fecha' => now(),
            'estado' => 'pendiente',
            'id_cliente' => $cliente->id_cliente,
            'id_usuario' => null, // Se asignará cuando un mesero lo tome
        ]);

        // Copiar items del pedido original
        foreach ($pedido->items as $item) {
            \App\Models\ItemPedido::create([
                'cantidad' => $item->cantidad,
                'precio_unitario' => $item->plato->precio, // Usar precio actual
                'notas_especiales' => $item->notas_especiales,
                'id_pedido' => $nuevoPedido->id_pedido,
                'id_plato' => $item->id_plato,
            ]);
        }

        return redirect()
            ->route('cliente.dashboard')
            ->with('success', "Pedido repetido correctamente. Nuevo pedido #$nuevoPedido->id_pedido creado.");
    }

    /**
     * Vista de Mis Pedidos del cliente.
     */
    public function pedidos(): View
    {
        $usuario = Auth::user();
        $cliente = $usuario->cliente;
        $pedidos = collect();

        if ($cliente) {
            $pedidos = Pedido::with([
                'items.plato',
                'pago',
            ])
                ->where('id_cliente', $cliente->id_cliente)
                ->orderByDesc('fecha')
                ->get();
        }

        return view('cliente.pedidos', compact('usuario', 'cliente', 'pedidos'));
    }

    /**
     * Vista de Mi Perfil del cliente.
     */
    public function perfil(): View
    {
        $usuario = Auth::user();
        $cliente = $usuario->cliente;

        return view('cliente.perfil', compact('usuario', 'cliente'));
    }
}
