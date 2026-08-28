<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MateriaPrima;
use App\Models\Receta;
use App\Models\Plato;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventarioController extends Controller
{
    /**
     * Mostrar el inventario.
     */
   public function index(): View
{
    $materias = MateriaPrima::orderBy('nombre')->get();

    $platos = Plato::with('recetas.materiaPrima')
        ->orderBy('nombre')
        ->get();

    return view('admin.inventario.index', compact(
        'materias',
        'platos'
    ));
}

    /**
     * Crear una materia prima.
     */
    public function storeMateria(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'stock_actual' => ['required', 'numeric', 'min:0'],
            'unidad_medida' => ['required', 'string', 'max:50'],
            'stock_minimo' => ['required', 'numeric', 'min:0'],
        ]);

        MateriaPrima::create($validated);

        return redirect()
            ->route('admin.inventario.index')
            ->with('success', 'Materia prima creada correctamente.');
    }

    /**
     * Actualizar una materia prima.
     */
    public function updateMateria(
        Request $request,
        MateriaPrima $materia
    ): RedirectResponse {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'stock_actual' => ['required', 'numeric', 'min:0'],
            'unidad_medida' => ['required', 'string', 'max:50'],
            'stock_minimo' => ['required', 'numeric', 'min:0'],
        ]);

        $materia->update($validated);

        return redirect()
            ->route('admin.inventario.index')
            ->with('success', 'Materia prima actualizada correctamente.');
    }

    /**
     * Aumentar el stock de una materia prima.
     *
     * Equivale a:
     * stock_actual = stock_actual + cantidad
     */
    public function updateStock(
        Request $request,
        MateriaPrima $materia
    ): RedirectResponse {
        $validated = $request->validate([
            'cantidad_a_sumar' => ['required', 'numeric', 'gt:0'],
        ]);

        $materia->increment(
            'stock_actual',
            $validated['cantidad_a_sumar']
        );

        $materia->touch();

        return redirect()
            ->route('admin.inventario.index')
            ->with('success', 'Stock actualizado correctamente.');
    }

    /**
     * Agregar un ingrediente a una receta.
     *
     * Si el ingrediente ya existe para ese plato,
     * suma la nueva cantidad.
     *
     * Si no existe, crea el ingrediente.
     */
    public function addIngrediente(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_plato' => ['required', 'exists:plato,id_plato'],
            'id_materia' => ['required', 'exists:materia_prima,id_materia'],
            'cantidad_requerida' => ['required', 'numeric', 'gt:0'],
        ]);

        $receta = Receta::where('id_plato', $validated['id_plato'])
            ->where('id_materia', $validated['id_materia'])
            ->first();

        if ($receta) {
            $receta->increment(
                'cantidad_requerida',
                $validated['cantidad_requerida']
            );

            $mensaje = 'Cantidad del ingrediente actualizada correctamente.';
        } else {
            Receta::create($validated);

            $mensaje = 'Ingrediente agregado a la receta correctamente.';
        }

        return redirect()
            ->route('admin.inventario.index')
            ->with('success', $mensaje);
    }

    /**
     * Eliminar un ingrediente de una receta.
     */
    public function destroyIngrediente(
        Receta $receta
    ): RedirectResponse {
        $receta->delete();

        return redirect()
            ->route('admin.inventario.index')
            ->with('success', 'Ingrediente eliminado de la receta.');
    }
}