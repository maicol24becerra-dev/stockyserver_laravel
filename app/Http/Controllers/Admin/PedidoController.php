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
    public function index(): View
    {
        $pedidos = Pedido::with([
            'cliente.usuario',
            'usuario',
            'items.plato',
            'pago',
        ])
            ->orderByDesc('fecha')
            ->get();

        return view('admin.pedidos.index', compact('pedidos'));
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
        ]);

        $usuario = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Crear pedido
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($validated, $usuario) {

            $pedido = Pedido::create([
                'fecha' => now(),
                'estado' => 'pendiente',
                'id_usuario' => $usuario->id_usuario,
                'id_cliente' => $validated['id_cliente'],
            ]);

            foreach ($validated['items'] as $item) {

                $plato = Plato::findOrFail(
                    $item['id_plato']
                );

                ItemPedido::create([
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $plato->precio,
                    'id_pedido' => $pedido->id_pedido,
                    'id_plato' => $plato->id_plato,
                ]);
            }
        });

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
                'in:pendiente,en preparación,listo,entregado',
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

            $pedido->update([
                'estado' => $nuevoEstado,
            ]);

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

            $transicionesMesero = [
                'pendiente' => 'en preparación',
                'listo' => 'entregado',
            ];

            if (
                !isset($transicionesMesero[$estadoActual])
                ||
                $transicionesMesero[$estadoActual] !== $nuevoEstado
            ) {
                abort(
                    403,
                    'El mesero no puede realizar este cambio de estado.'
                );
            }

            $pedido->update([
                'estado' => $nuevoEstado,
            ]);

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
}