<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private Role $role;

    protected function setUp(): void
    {
        parent::setUp();

        $this->role = Role::create([
            'nombre' => 'Administrador',
        ]);
    }

    /**
     * Un administrador puede ver el listado de usuarios.
     */
    public function test_administrador_puede_ver_usuarios(): void
    {
        $admin = $this->crearAdministrador();

        $response = $this->actingAs($admin)
            ->get(route('admin.usuarios.index'));

        $response->assertStatus(200);
        $response->assertSee('Administrador');
    }

    /**
     * Se puede crear un usuario.
     */
    public function test_puede_crear_usuario(): void
    {
        $admin = $this->crearAdministrador();

        $response = $this->actingAs($admin)
            ->post(route('admin.usuarios.store'), [
                'nombre' => 'Usuario Prueba',
                'correo' => 'usuario@test.com',
                'contrasena' => '12345678',
                'telefono' => '3001111111',
                'id_rol' => $this->role->id_rol,
                'estado' => 'activo',
            ]);

        $response->assertRedirect(route('admin.usuarios.index'));

        $this->assertDatabaseHas('usuario', [
            'nombre' => 'Usuario Prueba',
            'correo' => 'usuario@test.com',
            'telefono' => '3001111111',
            'id_rol' => $this->role->id_rol,
            'estado' => 'activo',
        ]);

        $usuario = User::where('correo', 'usuario@test.com')->first();

        $this->assertNotNull($usuario);
        $this->assertTrue(
            Hash::check('12345678', $usuario->contrasena)
        );
    }

    /**
     * Se puede ver un usuario.
     */
    public function test_puede_ver_un_usuario(): void
    {
        $admin = $this->crearAdministrador();

        $usuario = User::create([
            'nombre' => 'Usuario Prueba',
            'correo' => 'usuario@test.com',
            'contrasena' => '12345678',
            'telefono' => '3001111111',
            'id_rol' => $this->role->id_rol,
            'estado' => 'activo',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.usuarios.show', $usuario));

        $response->assertStatus(200);
        $response->assertSee('Usuario Prueba');
        $response->assertSee('usuario@test.com');
    }

    /**
     * Se puede editar un usuario.
     */
    public function test_puede_editar_usuario(): void
    {
        $admin = $this->crearAdministrador();

        $usuario = User::create([
            'nombre' => 'Usuario Original',
            'correo' => 'original@test.com',
            'contrasena' => '12345678',
            'telefono' => '3001111111',
            'id_rol' => $this->role->id_rol,
            'estado' => 'activo',
        ]);

        $response = $this->actingAs($admin)
            ->put(route('admin.usuarios.update', $usuario), [
                'nombre' => 'Usuario Editado',
                'correo' => 'editado@test.com',
                'contrasena' => '',
                'telefono' => '3002222222',
                'id_rol' => $this->role->id_rol,
                'estado' => 'activo',
            ]);

        $response->assertRedirect(route('admin.usuarios.index'));

        $usuario->refresh();

        $this->assertSame('Usuario Editado', $usuario->nombre);
        $this->assertSame('editado@test.com', $usuario->correo);
        $this->assertSame('3002222222', $usuario->telefono);

        $this->assertTrue(
            Hash::check('12345678', $usuario->contrasena)
        );
    }

    /**
     * Se puede eliminar un usuario.
     */
    public function test_puede_eliminar_usuario(): void
    {
        $admin = $this->crearAdministrador();

        $usuario = User::create([
            'nombre' => 'Usuario Para Eliminar',
            'correo' => 'eliminar@test.com',
            'contrasena' => '12345678',
            'telefono' => '3003333333',
            'id_rol' => $this->role->id_rol,
            'estado' => 'activo',
        ]);

        $response = $this->actingAs($admin)
            ->delete(route('admin.usuarios.destroy', $usuario));

        $response->assertRedirect(route('admin.usuarios.index'));

        $this->assertDatabaseMissing('usuario', [
            'id_usuario' => $usuario->id_usuario,
        ]);
    }

    /**
     * El administrador no puede eliminarse a sí mismo.
     */
    public function test_administrador_no_puede_eliminarse_a_si_mismo(): void
    {
        $admin = $this->crearAdministrador();

        $response = $this->actingAs($admin)
            ->delete(route('admin.usuarios.destroy', $admin));

        $response->assertRedirect(route('admin.usuarios.index'));

        $this->assertDatabaseHas('usuario', [
            'id_usuario' => $admin->id_usuario,
        ]);
    }

    /**
     * Crear administrador para las pruebas.
     */
    private function crearAdministrador(): User
    {
        return User::create([
            'nombre' => 'Administrador',
            'correo' => 'admin@test.com',
            'contrasena' => '12345678',
            'telefono' => '3000000000',
            'id_rol' => $this->role->id_rol,
            'estado' => 'activo',
        ]);
    }
}