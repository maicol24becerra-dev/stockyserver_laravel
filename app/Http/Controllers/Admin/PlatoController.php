<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plato;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlatoController extends Controller
{
    /**
     * Listar todos los platos.
     */
    public function index(): View
    {
        $platos = Plato::orderBy('nombre')->paginate(10);

        return view('admin.platos.index', compact('platos'));
    }

    /**
     * Mostrar formulario para crear plato.
     */
    public function create(): View
    {
        return view('admin.platos.create');
    }

    /**
     * Guardar nuevo plato.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'precio' => ['required', 'numeric', 'min:0'],
            'descripcion' => ['nullable', 'string'],
            'disponibilidad' => ['required', 'integer', 'min:0'],
            'categoria' => ['nullable', 'string', 'max:255'],
            'imagen' => ['nullable', 'string', 'max:255'],
        ]);

        Plato::create($validated);

        return redirect()
            ->route('admin.platos.index')
            ->with('success', 'Plato creado correctamente.');
    }

    /**
     * Mostrar un plato.
     */
    public function show(Plato $plato): View
    {
        return view('admin.platos.show', compact('plato'));
    }

    /**
     * Mostrar formulario para editar.
     */
    public function edit(Plato $plato): View
    {
        return view('admin.platos.edit', compact('plato'));
    }

    /**
     * Actualizar plato.
     */
    public function update(Request $request, Plato $plato): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'precio' => ['required', 'numeric', 'min:0'],
            'descripcion' => ['nullable', 'string'],
            'disponibilidad' => ['required', 'integer', 'min:0'],
            'categoria' => ['nullable', 'string', 'max:255'],
            'imagen' => ['nullable', 'string', 'max:255'],
        ]);

        $plato->update($validated);

        return redirect()
            ->route('admin.platos.index')
            ->with('success', 'Plato actualizado correctamente.');
    }

    /**
     * Eliminar plato.
     */
    public function destroy(Plato $plato): RedirectResponse
    {
        $plato->delete();

        return redirect()
            ->route('admin.platos.index')
            ->with('success', 'Plato eliminado correctamente.');
    }

    /**
     * Actualizar disponibilidad.
     */
    public function updateDisponibilidad(
        Request $request,
        Plato $plato
    ): RedirectResponse {
        $validated = $request->validate([
            'disponibilidad' => ['required', 'integer', 'min:0'],
        ]);

        $plato->update([
            'disponibilidad' => $validated['disponibilidad'],
        ]);

        return redirect()
            ->route('admin.platos.index')
            ->with('success', 'Disponibilidad actualizada correctamente.');
    }
}