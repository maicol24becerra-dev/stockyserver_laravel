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
        $pago = Pago::create([
            'monto_pagado' => $validated['monto_pagado'],
            'metodo_pago' => $validated['metodo_pago'],
            'fecha_pago' => $validated['fecha_pago'],
            'id_pedido' => $pedido->id_pedido,
        ]);

        // Si viene desde mesero dashboard, redirigir a factura
        $usuario = $request->user();
        $rol = $usuario->role?->nombre ?? '';
        
        if ($rol === 'Mesero') {
            return redirect()
                ->route('mesero.factura', ['pedido' => $pedido->id_pedido])
                ->with('pago_registrado', true);
        }

        return redirect()
            ->route('admin.pedidos.show', $pedido)
            ->with(
                'success',
                'Pago registrado correctamente.'
            );
    }

    /**
     * Mostrar factura imprimible del pedido
     */
    public function factura(Pedido $pedido): View
    {
        /*
        |--------------------------------------------------------------------------
        | CRÍTICO: Validar estado entregado antes de generar factura (HU-16)
        |--------------------------------------------------------------------------
        | Solo se puede generar factura si el pedido está en estado "entregado"
        */

        $estado = strtolower(trim($pedido->estado));

        if ($estado !== 'entregado') {
            abort(403, 'Solo se puede generar factura para pedidos entregados.');
        }

        $pedido->load([
            'cliente.usuario',
            'items.plato',
            'pago'
        ]);

        $total = $pedido->items->sum(fn ($item) => $item->cantidad * $item->precio_unitario);
        $subtotal = $total;
        $descuento = 0;

        return view('mesero.factura', compact('pedido', 'total', 'subtotal', 'descuento'));
    }

    /**
     * División de cuenta - mostrar formulario (HU-16)
     */
    public function dividirCuenta(Pedido $pedido)
    {
        $estado = strtolower(trim($pedido->estado));

        if ($estado !== 'entregado') {
            return back()->withErrors(['error' => 'Solo se puede dividir la cuenta de pedidos entregados.']);
        }

        $pedido->load(['items.plato', 'cliente.usuario']);
        $total = $pedido->items->sum(fn ($item) => $item->cantidad * $item->precio_unitario);

        return view('admin.pagos.dividir', compact('pedido', 'total'));
    }

    /**
     * Procesar división de cuenta (HU-16)
     */
    public function procesarDivision(Request $request, Pedido $pedido)
    {
        $validated = $request->validate([
            'divisiones' => 'required|array|min:1',
            'divisiones.*.descripcion' => 'required|string|max:255',
            'divisiones.*.monto' => 'required|numeric|min:0.01',
            'divisiones.*.metodo_pago' => 'required|in:efectivo,tarjeta,transferencia,nequi,daviplata',
        ]);

        $total = $pedido->items->sum(fn ($item) => $item->cantidad * $item->precio_unitario);
        $totalDivisiones = collect($validated['divisiones'])->sum('monto');

        // Validar que la suma de divisiones coincida con el total
        if (abs($totalDivisiones - $total) > 0.01) {
            return back()->withErrors([
                'divisiones' => "La suma de las divisiones ($" . number_format($totalDivisiones, 2) . ") debe ser igual al total ($" . number_format($total, 2) . ")"
            ]);
        }

        // Eliminar pago anterior si existe
        if ($pedido->pago) {
            $pedido->pago->delete();
        }

        // Crear registros de pago para cada división
        foreach ($validated['divisiones'] as $index => $division) {
            Pago::create([
                'monto_pagado' => $division['monto'],
                'metodo_pago' => $division['metodo_pago'],
                'fecha_pago' => now(),
                'id_pedido' => $pedido->id_pedido,
                'observaciones' => 'División ' . ($index + 1) . ': ' . $division['descripcion'],
            ]);
        }

        return redirect()
            ->route('mesero.dashboard', ['seccion' => 'activos'])
            ->with('success', 'Cuenta dividida correctamente en ' . count($validated['divisiones']) . ' pagos.');
    }
}