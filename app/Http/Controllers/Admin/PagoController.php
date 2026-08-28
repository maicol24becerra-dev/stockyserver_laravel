<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Pedido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PagoController extends Controller
{
    /**
     * Mostrar formulario para registrar el pago de un pedido.
     */
    public function create(Pedido $pedido): View|RedirectResponse
    {
        /*
         * El pago solamente se puede registrar
         * cuando el pedido está entregado.
         */
        if ($pedido->estado !== 'entregado') {
            return redirect()
                ->route('admin.pedidos.show', $pedido)
                ->with(
                    'error',
                    'El pago solamente puede registrarse cuando el pedido esté entregado.'
                );
        }

        /*
         * Si el pedido ya tiene pago,
         * no permitimos registrar otro.
         */
        if ($pedido->pago) {
            return redirect()
                ->route('admin.pedidos.show', $pedido)
                ->with(
                    'error',
                    'Este pedido ya tiene un pago registrado.'
                );
        }

        $pedido->load([
            'cliente.usuario',
            'usuario',
            'items.plato',
            'pago',
        ]);

        return view('admin.pagos.create', compact('pedido'));
    }


    /**
     * Registrar el pago de un pedido.
     */
    public function store(
        Request $request,
        Pedido $pedido
    ): RedirectResponse {
        /*
         * Verificar que el pedido esté entregado.
         */
        if ($pedido->estado !== 'entregado') {
            return redirect()
                ->route('admin.pedidos.show', $pedido)
                ->with(
                    'error',
                    'El pago solamente puede registrarse cuando el pedido esté entregado.'
                );
        }

        /*
         * Verificar que no exista otro pago.
         */
        if ($pedido->pago) {
            return redirect()
                ->route('admin.pedidos.show', $pedido)
                ->with(
                    'error',
                    'Este pedido ya tiene un pago registrado.'
                );
        }

        /*
         * Validar los datos enviados.
         */
        $validated = $request->validate([
            'monto_pagado' => [
                'required',
                'numeric',
                'min:0',
            ],

            'metodo_pago' => [
                'required',
                'string',
                'max:50',
            ],

            'fecha_pago' => [
                'required',
                'date',
            ],
        ]);

        /*
         * Cargar los items del pedido.
         */
        $pedido->load('items');

        /*
         * Calcular el total del pedido.
         */
        $total = $pedido->items->sum(function ($item) {
            return $item->cantidad * $item->precio_unitario;
        });

        /*
         * El monto pagado debe ser igual
         * al total del pedido.
         */
        if ((float) $validated['monto_pagado'] !== (float) $total) {
            return back()
                ->withInput()
                ->withErrors([
                    'monto_pagado' =>
                        'El monto pagado debe ser igual al total del pedido: $' .
                        number_format($total, 0, ',', '.'),
                ]);
        }

        /*
         * Registrar el pago.
         *
         * IMPORTANTE:
         * NO cambiamos el estado del pedido.
         */
        Pago::create([
            'monto_pagado' => $validated['monto_pagado'],
            'metodo_pago' => $validated['metodo_pago'],
            'fecha_pago' => $validated['fecha_pago'],
            'id_pedido' => $pedido->id_pedido,
        ]);

        return redirect()
            ->route('admin.pedidos.show', $pedido)
            ->with(
                'success',
                'Pago registrado correctamente.'
            );
    }
}