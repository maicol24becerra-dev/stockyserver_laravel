<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = Categoria::withCount('platos')
            ->orderBy('nombre')
            ->paginate(15);

        return view('admin.categorias.index', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'unique:categoria,nombre'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'icono' => ['nullable', 'string', 'max:50'],
            'activo' => ['boolean'],
        ]);

        Categoria::create($validated);

        return redirect()
            ->route('admin.categorias.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        $categoria->loadCount('platos');
        $platos = $categoria->platos()->paginate(10);

        return view('admin.categorias.show', compact('categoria', 'platos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        return view('admin.categorias.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'unique:categoria,nombre,' . $categoria->id_categoria . ',id_categoria'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'icono' => ['nullable', 'string', 'max:50'],
            'activo' => ['boolean'],
        ]);

        $categoria->update($validated);

        return redirect()
            ->route('admin.categorias.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        // Verificar si tiene platos asociados
        $platosCount = $categoria->platos()->count();

        if ($platosCount > 0) {
            return back()->withErrors([
                'categoria' => sprintf(
                    'No se puede eliminar la categoría "%s" porque tiene %d plato(s) asociado(s).',
                    $categoria->nombre,
                    $platosCount
                ),
            ]);
        }

        $categoria->delete();

        return redirect()
            ->route('admin.categorias.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }
}
