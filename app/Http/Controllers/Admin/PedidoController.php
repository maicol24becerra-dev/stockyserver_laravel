<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\ItemPedido;
use App\Models\Pedido;
use App\Models\Plato;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PedidoController extends Controller
{
    /**
     * Mostrar todos los pedidos.
     */
    public function index(Request $request): View
    {
        $query = Pedido::with([
            'cliente.usuario',
            'usuario',
            'items.plato',
            'pago',
        ]);

        $allPedidos = (clone $query)->get();

        // Contadores para pestañas
        $countTodos = $allPedidos->count();
        $countPendientes = $allPedidos->filter(fn($p) => strtolower(trim($p->estado)) === 'pendiente')->count();
        $countPreparacion = $allPedidos->filter(fn($p) => strtolower(trim($p->estado)) === 'en preparación')->count();
        $countEntregados = $allPedidos->filter(fn($p) => strtolower(trim($p->estado)) === 'entregado')->count();
        $countPagados = $allPedidos->filter(fn($p) => $p->pago !== null)->count();
        $countCancelados = $allPedidos->filter(fn($p) => strtolower(trim($p->estado)) === 'cancelado')->count();

        $estadoFiltro = strtolower(trim($request->get('estado', 'todos')));
        if ($estadoFiltro !== 'todos' && !empty($estadoFiltro)) {
            if ($estadoFiltro === 'pagados') {
                $query->whereHas('pago');
            } else {
                $query->where('estado', $estadoFiltro);
            }
        }

        $pedidos = $this->getPedidosConPrioridad($query)->get();

        return view('admin.pedidos.index', compact(
            'pedidos',
            'allPedidos',
            'countTodos',
            'countPendientes',
            'countPreparacion',
            'countEntregados',
            'countPagados',
            'countCancelados',
            'estadoFiltro'
        ));
    }

    /**
     * Mostrar formulario para crear un pedido.
     */
    public function create(): View
    {
        $clientes = Cliente::with('usuario')->get();

        $platos = Plato::where('disponibilidad', 1)
            ->orderBy('nombre')
            ->get();

        return view(
            'admin.pedidos.create',
            compact('clientes', 'platos')
        );
    }

    /**
     * Registrar un nuevo pedido.
     *
     * Todo pedido nuevo comienza como:
     * pendiente
     */
    public function store(Request $request): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Eliminar platos con cantidad 0
        |--------------------------------------------------------------------------
        */

        $items = collect($request->input('items', []))
            ->filter(function ($item) {
                return isset($item['cantidad'])
                    && (int) $item['cantidad'] > 0;
            })
            ->values()
            ->all();

        $request->merge([
            'items' => $items,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validación
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'id_cliente' => [
                'required',
                'exists:cliente,id_cliente',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.id_plato' => [
                'required',
                'exists:plato,id_plato',
            ],

            'items.*.cantidad' => [
                'required',
                'integer',
                'min:1',
            ],

            'items.*.notas_especiales' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        $usuario = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Crear pedido
        |--------------------------------------------------------------------------
        */

        $pedidoId = null;

        DB::transaction(function () use ($validated, $usuario, &$pedidoId) {

            $pedido = Pedido::create([
                'fecha' => now(),
                'estado' => 'pendiente',
                'id_usuario' => $usuario->id_usuario,
                'id_cliente' => $validated['id_cliente'],
            ]);

            $pedidoId = $pedido->id_pedido;

            foreach ($validated['items'] as $item) {

                $plato = Plato::findOrFail(
                    $item['id_plato']
                );

                ItemPedido::create([
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $plato->precio,
                    'notas_especiales' => $item['notas_especiales'] ?? null,
                    'id_pedido' => $pedido->id_pedido,
                    'id_plato' => $plato->id_plato,
                ]);
            }
        });

        // Redirigir al dashboard de mesero con mensaje de éxito
        $rol = $usuario->role?->nombre ?? '';
        
        if ($rol === 'Mesero') {
            return redirect()
                ->route('mesero.dashboard', ['seccion' => 'activos'])
                ->with('pedido_creado', $pedidoId);
        }

        return redirect()
            ->route('admin.pedidos.index')
            ->with(
                'success',
                'Pedido creado correctamente.'
            );
    }

    /**
     * Mostrar detalle de un pedido.
     */
    public function show(Pedido $pedido): View
    {
        $pedido->load([
            'cliente.usuario',
            'usuario',
            'items.plato',
            'pago',
        ]);

        return view(
            'admin.pedidos.show',
            compact('pedido')
        );
    }

    /**
     * Cambiar estado del pedido.
     *
     * Estados válidos:
     *
     * pendiente
     * en preparación
     * listo
     * entregado
     *
     * Permisos:
     *
     * Administrador:
     * Puede cambiar a cualquier estado válido.
     *
     * Mesero:
     * pendiente → en preparación
     * listo → entregado
     *
     * Cocinero:
     * en preparación → listo
     *
     * El pago es independiente del estado del pedido.
     */
    public function updateEstado(
        Request $request,
        Pedido $pedido
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Validar estado solicitado
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'estado' => [
                'required',
                'string',
                'in:pendiente,en preparación,listo,entregado,cancelado',
            ],
        ]);

        $nuevoEstado = strtolower(
            trim($validated['estado'])
        );

        $estadoActual = strtolower(
            trim($pedido->estado)
        );

        /*
        |--------------------------------------------------------------------------
        | Obtener usuario autenticado
        |--------------------------------------------------------------------------
        */

        $usuario = $request->user();

        if (!$usuario) {
            abort(
                403,
                'Debes iniciar sesión para realizar esta acción.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Obtener rol
        |--------------------------------------------------------------------------
        */

        $rol = trim(
            $usuario->role?->nombre ?? ''
        );

        if (!$rol) {
            abort(
                403,
                'El usuario no tiene un rol asignado.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ADMINISTRADOR
        |--------------------------------------------------------------------------
        |
        | El administrador tiene control total.
        |
        | Puede cambiar el pedido a cualquiera de estos estados:
        |
        | pendiente
        | en preparación
        | listo
        | entregado
        |
        */

        if ($rol === 'Administrador') {

            $estadosPermitidos = [
                'pendiente',
                'en preparación',
                'listo',
                'entregado',
                'cancelado',
            ];

            if (!in_array(
                $nuevoEstado,
                $estadosPermitidos,
                true
            )) {
                abort(
                    403,
                    'El estado solicitado no es válido.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | CRÍTICO: Descuento automático de inventario (HU-06)
            |--------------------------------------------------------------------------
            | Cuando un pedido cambia a "en preparación", se descuentan automáticamente
            | las materias primas del inventario según las recetas de los platos.
            */

            if ($nuevoEstado === 'en preparación' && $estadoActual !== 'en preparación') {
                $this->descontarInventario($pedido);
            }

            $pedido->update([
                'estado' => $nuevoEstado,
            ]);

            // Verificar si debe redirigir al dashboard de mesero
            if ($request->input('return_to') === 'mesero') {
                return redirect()
                    ->route('mesero.dashboard', ['seccion' => 'activos'])
                    ->with('estado_actualizado', [
                        'pedido_id' => $pedido->id_pedido,
                        'estado' => ucfirst($nuevoEstado)
                    ]);
            }

            return back()->with(
                'success',
                'Estado del pedido actualizado correctamente.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | MESERO
        |--------------------------------------------------------------------------
        |
        | El mesero solamente puede realizar:
        |
        | pendiente → en preparación
        |
        | listo → entregado
        |
        */

        if ($rol === 'Mesero') {

            // Transiciones permitidas para meseros
            $transicionesPermitidas = [
                'pendiente' => ['en preparación', 'cancelado'],
                'listo' => ['entregado'],
                'en preparación' => ['cancelado'], // Permitir cancelar desde en preparación
            ];

            if (
                !isset($transicionesPermitidas[$estadoActual])
                ||
                !in_array($nuevoEstado, $transicionesPermitidas[$estadoActual])
            ) {
                abort(
                    403,
                    'El mesero no puede realizar este cambio de estado.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | CRÍTICO: Descuento automático de inventario (HU-06)
            |--------------------------------------------------------------------------
            */

            if ($nuevoEstado === 'en preparación' && $estadoActual !== 'en preparación') {
                $this->descontarInventario($pedido);
            }

            // Si se cancela un pedido en preparación, restaurar inventario
            if ($nuevoEstado === 'cancelado' && $estadoActual === 'en preparación') {
                $this->restaurarInventario($pedido);
            }

            $pedido->update([
                'estado' => $nuevoEstado,
            ]);

            // Verificar si debe redirigir al dashboard de mesero
            if ($request->input('return_to') === 'mesero') {
                return redirect()
                    ->route('mesero.dashboard', ['seccion' => 'activos'])
                    ->with('estado_actualizado', [
                        'pedido_id' => $pedido->id_pedido,
                        'estado' => ucfirst($nuevoEstado)
                    ]);
            }

            return back()->with(
                'success',
                'Estado del pedido actualizado correctamente.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | COCINERO
        |--------------------------------------------------------------------------
        |
        | El cocinero solamente puede realizar:
        |
        | en preparación → listo
        |
        */

        if ($rol === 'Cocinero') {

            if (
                $estadoActual !== 'en preparación'
                ||
                $nuevoEstado !== 'listo'
            ) {
                abort(
                    403,
                    'El cocinero solamente puede marcar como listo un pedido en preparación.'
                );
            }

            $pedido->update([
                'estado' => $nuevoEstado,
            ]);

            return back()->with(
                'success',
                'Pedido marcado como listo.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CLIENTE U OTRO ROL
        |--------------------------------------------------------------------------
        |
        | Los clientes no pueden cambiar estados.
        |
        */

        abort(
            403,
            'No tienes permisos para cambiar el estado del pedido.'
        );
    }

    /**
     * Agregar item a un pedido existente.
     * Solo permitido si el pedido está en estado "pendiente"
     */
    public function addItem(Request $request, Pedido $pedido)
    {
        // Verificar que el pedido esté en estado pendiente
        $estado = strtolower(trim($pedido->estado));
        
        if ($estado !== 'pendiente') {
            return response()->json([
                'success' => false,
                'message' => 'Solo se pueden agregar items a pedidos pendientes'
            ], 400);
        }

        // Validar datos
        $validated = $request->validate([
            'id_plato' => 'required|exists:plato,id_plato',
            'cantidad' => 'required|integer|min:1',
        ]);

        // Buscar el plato
        $plato = Plato::findOrFail($validated['id_plato']);

        // Crear el item
        ItemPedido::create([
            'cantidad' => $validated['cantidad'],
            'precio_unitario' => $plato->precio,
            'id_pedido' => $pedido->id_pedido,
            'id_plato' => $plato->id_plato,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item agregado correctamente'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Descontar inventario automáticamente (HU-06)
    |--------------------------------------------------------------------------
    | Cuando un pedido pasa a "en preparación", se descuentan las materias
    | primas según las recetas de los platos del pedido.
    */
    private function descontarInventario(Pedido $pedido): void
    {
        $alertas = [];

        // Cargar items con platos y sus recetas
        $pedido->load('items.plato.recetas.materiaPrima');

        foreach ($pedido->items as $item) {
            $plato = $item->plato;
            
            if (!$plato) {
                continue;
            }

            // Iterar las recetas del plato
            foreach ($plato->recetas as $receta) {
                $materiaPrima = $receta->materiaPrima;
                
                if (!$materiaPrima) {
                    continue;
                }

                // Calcular cantidad total a descontar (cantidad_requerida * cantidad de platos)
                $cantidadDescontar = $receta->cantidad_requerida * $item->cantidad;

                // Descontar del inventario
                $materiaPrima->stock_actual -= $cantidadDescontar;
                $materiaPrima->save();

                // Verificar si llegó al stock mínimo
                if ($materiaPrima->stock_actual <= $materiaPrima->stock_minimo) {
                    $alertas[] = sprintf(
                        '⚠️ ALERTA: %s ha alcanzado el stock mínimo (Disponible: %.2f %s, Mínimo: %.2f %s)',
                        $materiaPrima->nombre,
                        $materiaPrima->stock_actual,
                        $materiaPrima->unidad_medida ?? '',
                        $materiaPrima->stock_minimo,
                        $materiaPrima->unidad_medida ?? ''
                    );
                }
            }
        }

        // Si hay alertas, guardarlas en sesión para mostrarlas
        if (!empty($alertas)) {
            session()->flash('alertas_inventario', $alertas);
        }
    }

    /**
     * Reversión de estados - cambiar estado hacia atrás (HU-19)
     */
    public function revertirEstado(Pedido $pedido)
    {
        $estadoActual = strtolower(trim($pedido->estado));
        $nuevoEstado = null;

        // Definir el flujo inverso de estados
        switch ($estadoActual) {
            case 'en preparación':
                $nuevoEstado = 'pendiente';
                // Restaurar inventario al revertir de "en preparación" a "pendiente"
                $this->restaurarInventario($pedido);
                break;
            case 'listo':
                $nuevoEstado = 'en preparación';
                break;
            case 'entregado':
                $nuevoEstado = 'listo';
                break;
            default:
                return back()->withErrors(['error' => 'No se puede revertir el estado de este pedido.']);
        }

        // Validar que no se pueda revertir si ya hay pago registrado
        if ($estadoActual === 'entregado' && $pedido->pago) {
            return back()->withErrors(['error' => 'No se puede revertir un pedido que ya tiene pago registrado.']);
        }

        $pedido->update(['estado' => $nuevoEstado]);

        return redirect()
            ->route('admin.pedidos.index')
            ->with('success', "Pedido revertido de '{$estadoActual}' a '{$nuevoEstado}' correctamente.");
    }

    /**
     * Restaurar inventario cuando se revierte de "en preparación" a "pendiente"
     */
    private function restaurarInventario(Pedido $pedido)
    {
        $pedido->load(['items.plato.recetas.materiaPrima']);

        foreach ($pedido->items as $item) {
            $plato = $item->plato;
            if ($plato && $plato->recetas->isNotEmpty()) {
                foreach ($plato->recetas as $receta) {
                    $materiaPrima = $receta->materiaPrima;
                    if ($materiaPrima) {
                        $cantidadARestaurar = $receta->cantidad_requerida * $item->cantidad;
                        
                        // Restaurar stock
                        $materiaPrima->increment('stock_actual', $cantidadARestaurar);
                    }
                }
            }
        }
    }

    /**
     * Actualizar prioridad de pedido (HU-21)
     */
    public function actualizarPrioridad(Request $request, Pedido $pedido)
    {
        $validated = $request->validate([
            'prioridad' => 'required|in:baja,normal,alta,urgente'
        ]);

        $prioridadAnterior = $pedido->prioridad;
        $pedido->update(['prioridad' => $validated['prioridad']]);

        return back()->with('success', 
            "Prioridad actualizada de '{$prioridadAnterior}' a '{$validated['prioridad']}' correctamente."
        );
    }

    /**
     * Obtener pedidos ordenados por prioridad
     */
    private function getPedidosConPrioridad($query)
    {
        // Orden de prioridad: urgente > alta > normal > baja
        return $query->orderByRaw("
            CASE prioridad 
                WHEN 'urgente' THEN 1 
                WHEN 'alta' THEN 2 
                WHEN 'normal' THEN 3 
                WHEN 'baja' THEN 4 
                ELSE 5 
            END
        ")->orderBy('fecha', 'asc');
    }

    /**
     * Cancelar pedido (para Meseros y Administradores)
     */
    public function cancelarPedido(Pedido $pedido)
    {
        $estadoActual = strtolower(trim($pedido->estado));

        // Solo se pueden cancelar pedidos pendientes o en preparación
        if (!in_array($estadoActual, ['pendiente', 'en preparación'])) {
            return back()->withErrors(['error' => 'Solo se pueden cancelar pedidos pendientes o en preparación.']);
        }

        // Si está en preparación, restaurar inventario
        if ($estadoActual === 'en preparación') {
            $this->restaurarInventario($pedido);
        }

        $pedido->update(['estado' => 'cancelado']);

        return back()->with('success', 'Pedido cancelado correctamente.');
    }
}
