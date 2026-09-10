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
        $categorias = \App\Models\Categoria::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('admin.platos.create', compact('categorias'));
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
            'id_categoria' => ['nullable', 'exists:categoria,id_categoria'],
            'imagen' => ['nullable', 'string', 'max:255'],
            'calorias' => ['nullable', 'integer', 'min:0'],
            'proteinas' => ['nullable', 'numeric', 'min:0'],
            'carbohidratos' => ['nullable', 'numeric', 'min:0'],
            'grasas' => ['nullable', 'numeric', 'min:0'],
            'alergenos' => ['nullable', 'string'],
            'vegetariano' => ['boolean'],
            'vegano' => ['boolean'],
            'sin_gluten' => ['boolean'],
            'ingredientes_principales' => ['nullable', 'string'],
            'tiempo_preparacion' => ['nullable', 'integer', 'min:0'],
            'nivel_picante' => ['nullable', 'in:ninguno,bajo,medio,alto'],
        ]);

        if ($request->hasFile('imagen_file')) {
            $path = $request->file('imagen_file')->store('platos', 'public');
            $validated['imagen'] = $path;
        }

        if (!isset($validated['disponibilidad'])) {
            $validated['disponibilidad'] = 1;
        }

        Plato::create($validated);

        return redirect()
            ->back()
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
        $categorias = \App\Models\Categoria::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('admin.platos.edit', compact('plato', 'categorias'));
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
            'id_categoria' => ['nullable', 'exists:categoria,id_categoria'],
            'imagen' => ['nullable', 'string', 'max:255'],
            'calorias' => ['nullable', 'integer', 'min:0'],
            'proteinas' => ['nullable', 'numeric', 'min:0'],
            'carbohidratos' => ['nullable', 'numeric', 'min:0'],
            'grasas' => ['nullable', 'numeric', 'min:0'],
            'alergenos' => ['nullable', 'string'],
            'vegetariano' => ['boolean'],
            'vegano' => ['boolean'],
            'sin_gluten' => ['boolean'],
            'ingredientes_principales' => ['nullable', 'string'],
            'tiempo_preparacion' => ['nullable', 'integer', 'min:0'],
            'nivel_picante' => ['nullable', 'in:ninguno,bajo,medio,alto'],
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