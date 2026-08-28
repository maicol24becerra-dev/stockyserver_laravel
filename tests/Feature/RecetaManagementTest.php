<?php

namespace Tests\Feature;

use App\Models\MateriaPrima;
use App\Models\Plato;
use App\Models\Receta;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecetaManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrador_puede_agregar_ingrediente_a_un_plato(): void
    {
        $rol = Role::create([
            'nombre' => 'Administrador',
        ]);

        $usuario = User::factory()->create([
            'id_rol' => $rol->id_rol,
        ]);

        $plato = Plato::create([
            'nombre' => 'Churrasco',
            'precio' => 43000,
            'descripcion' => 'Lomo de res asado',
            'disponibilidad' => 1,
            'categoria' => 'Carnes',
            'imagen' => null,
        ]);

        $materia = MateriaPrima::create([
            'nombre' => 'Tomate Rojo',
            'stock_actual' => 15,
            'unidad_medida' => 'kg',
            'stock_minimo' => 2,
        ]);

        $response = $this
            ->actingAs($usuario)
            ->post(route('admin.inventario.recetas.store'), [
                'id_plato' => $plato->id_plato,
                'id_materia' => $materia->id_materia,
                'cantidad_requerida' => 0.2,
            ]);

        $response->assertRedirect(
            route('admin.inventario.index')
        );

        $this->assertDatabaseHas('receta', [
            'id_plato' => $plato->id_plato,
            'id_materia' => $materia->id_materia,
            'cantidad_requerida' => 0.2,
        ]);
    }

    public function test_si_el_ingrediente_ya_existe_se_suma_la_cantidad(): void
    {
        $rol = Role::create([
            'nombre' => 'Administrador',
        ]);

        $usuario = User::factory()->create([
            'id_rol' => $rol->id_rol,
        ]);

        $plato = Plato::create([
            'nombre' => 'Churrasco',
            'precio' => 43000,
            'descripcion' => 'Lomo de res asado',
            'disponibilidad' => 1,
            'categoria' => 'Carnes',
            'imagen' => null,
        ]);

        $materia = MateriaPrima::create([
            'nombre' => 'Tomate Rojo',
            'stock_actual' => 15,
            'unidad_medida' => 'kg',
            'stock_minimo' => 2,
        ]);

        Receta::create([
            'id_plato' => $plato->id_plato,
            'id_materia' => $materia->id_materia,
            'cantidad_requerida' => 0.2,
        ]);

        $this
            ->actingAs($usuario)
            ->post(route('admin.inventario.recetas.store'), [
                'id_plato' => $plato->id_plato,
                'id_materia' => $materia->id_materia,
                'cantidad_requerida' => 0.3,
            ]);

        $this->assertDatabaseHas('receta', [
            'id_plato' => $plato->id_plato,
            'id_materia' => $materia->id_materia,
            'cantidad_requerida' => 0.5,
        ]);
    }
}