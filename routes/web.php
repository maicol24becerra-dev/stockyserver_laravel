<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\PasswordResetController;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\InventarioController;
use App\Http\Controllers\Admin\PlatoController;
use App\Http\Controllers\Admin\PedidoController;
use App\Http\Controllers\Admin\PagoController;
use App\Http\Controllers\Admin\DashboardController;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Plato;

use App\Http\Controllers\Cliente\ClienteController;
use App\Http\Controllers\Cocinero\CocineroController;


/*
|--------------------------------------------------------------------------
| Página principal pública
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');


/*
|--------------------------------------------------------------------------
| Autenticación
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| Mostrar inicio de sesión
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');


/*
|--------------------------------------------------------------------------
| Procesar inicio de sesión
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.store');


/*
|--------------------------------------------------------------------------
| Cerrar sesión
|--------------------------------------------------------------------------
*/

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Registro público de clientes
|--------------------------------------------------------------------------
|
| Cualquier persona puede registrarse.
| RegistroController debe asignar automáticamente
| el rol "Cliente".
|
*/

Route::get('/register', [RegistroController::class, 'create'])
    ->name('register');

Route::post('/register', [RegistroController::class, 'store'])
    ->name('register.store');


/*
|--------------------------------------------------------------------------
| Recuperación de contraseña
|--------------------------------------------------------------------------
|
| Estas rutas son públicas.
| PasswordResetController debe verificar que el correo
| pertenezca a un usuario con rol Cliente.
|
*/


/*
|--------------------------------------------------------------------------
| Mostrar formulario "Olvidé mi contraseña"
|--------------------------------------------------------------------------
*/

Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])
    ->name('password.request');


/*
|--------------------------------------------------------------------------
| Solicitar código de recuperación
|--------------------------------------------------------------------------
*/

Route::post('/forgot-password', [PasswordResetController::class, 'sendCode'])
    ->name('password.email');


/*
|--------------------------------------------------------------------------
| Mostrar formulario para cambiar contraseña
|--------------------------------------------------------------------------
*/

Route::get('/reset-password', [PasswordResetController::class, 'showResetForm'])
    ->name('password.reset');


/*
|--------------------------------------------------------------------------
| Guardar nueva contraseña
|--------------------------------------------------------------------------
*/

Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])
    ->name('password.update');


/*
|--------------------------------------------------------------------------
| Rutas autenticadas
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'prevent-back-history'])->group(function () {


    /*
    |--------------------------------------------------------------------------
    | Dashboard Administrador
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:Administrador')
        ->name('admin.dashboard');


    /*
    |--------------------------------------------------------------------------
    | Dashboard Mesero
    |--------------------------------------------------------------------------
    */

    Route::get('/mesero/dashboard', function () {
        $pedidos = Pedido::with(['cliente.usuario', 'usuario', 'items.plato', 'pago'])
            ->orderByRaw("
                CASE prioridad 
                    WHEN 'urgente' THEN 1 
                    WHEN 'alta' THEN 2 
                    WHEN 'normal' THEN 3 
                    WHEN 'baja' THEN 4 
                    ELSE 5 
                END
            ")
            ->orderBy('fecha', 'asc')
            ->get();

        return view('mesero.dashboard', [
            'pedidos' => $pedidos,
            'clientes' => Cliente::with('usuario')->get(),
            'platos' => Plato::whereIn('disponibilidad', [1, '1', 'Disponible', 'disponible'])
                ->orderBy('nombre')
                ->get(),
        ]);
    })
        ->middleware('role:Mesero')
        ->name('mesero.dashboard');


    /*
    |--------------------------------------------------------------------------
    | Dashboard y Historial Cocinero
    |--------------------------------------------------------------------------
    */

    Route::get('/cocinero/dashboard', [CocineroController::class, 'dashboard'])
        ->middleware('role:Cocinero')
        ->name('cocinero.dashboard');

    Route::get('/cocinero/historial', [CocineroController::class, 'historial'])
        ->middleware('role:Cocinero')
        ->name('cocinero.historial');


    /*
    |--------------------------------------------------------------------------
    | Dashboard Cliente
    |--------------------------------------------------------------------------
    */

    Route::get('/cliente/dashboard', [ClienteController::class, 'dashboard'])
        ->middleware('role:Cliente')
        ->name('cliente.dashboard');


    Route::get('/cliente/menu', [ClienteController::class, 'menu'])
        ->middleware('role:Cliente')
        ->name('cliente.menu');

    Route::get('/cliente/pedidos', [ClienteController::class, 'pedidos'])
        ->middleware('role:Cliente')
        ->name('cliente.pedidos');

    Route::get('/cliente/perfil', [ClienteController::class, 'perfil'])
        ->middleware('role:Cliente')
        ->name('cliente.perfil');

    /*
    |--------------------------------------------------------------------------
    | Repetir pedido anterior (HU-13)
    |--------------------------------------------------------------------------
    */

    Route::post('/cliente/repetir-pedido/{pedido}', [ClienteController::class, 'repetirPedido'])
        ->middleware('role:Cliente')
        ->name('cliente.repetir-pedido');


    /*
    |--------------------------------------------------------------------------
    | Gestión de Usuarios
    |--------------------------------------------------------------------------
    | Solo Administrador
    |--------------------------------------------------------------------------
    */

    Route::resource('/admin/usuarios', UserController::class)
        ->names('admin.usuarios')
        ->middleware('role:Administrador');


    /*
    |--------------------------------------------------------------------------
    | Cambiar contraseña de usuario
    |--------------------------------------------------------------------------
    | Solo Administrador
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/usuarios/{usuario}/password',
        [UserController::class, 'editPassword']
    )
        ->middleware('role:Administrador')
        ->name('admin.usuarios.password.edit');


    Route::put(
        '/admin/usuarios/{usuario}/password',
        [UserController::class, 'updatePassword']
    )
        ->middleware('role:Administrador')
        ->name('admin.usuarios.password.update');


    /*
    |--------------------------------------------------------------------------
    | Activar cuenta
    |--------------------------------------------------------------------------
    | Solo Administrador
    |--------------------------------------------------------------------------
    */

    Route::put(
        '/admin/usuarios/{usuario}/activar',
        [UserController::class, 'activar']
    )
        ->middleware('role:Administrador')
        ->name('admin.usuarios.activar');


    /*
    |--------------------------------------------------------------------------
    | Desactivar cuenta
    |--------------------------------------------------------------------------
    | Solo Administrador
    |--------------------------------------------------------------------------
    */

    Route::put(
        '/admin/usuarios/{usuario}/desactivar',
        [UserController::class, 'desactivar']
    )
        ->middleware('role:Administrador')
        ->name('admin.usuarios.desactivar');


    /*
    |--------------------------------------------------------------------------
    | Gestión de Inventario
    |--------------------------------------------------------------------------
    | Solo Administrador
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/inventario', [InventarioController::class, 'index'])
        ->middleware('role:Administrador')
        ->name('admin.inventario.index');


    Route::post('/admin/inventario/materias', [InventarioController::class, 'storeMateria'])
        ->middleware('role:Administrador')
        ->name('admin.inventario.materias.store');


    Route::put('/admin/inventario/materias/{materia}', [InventarioController::class, 'updateMateria'])
        ->middleware('role:Administrador')
        ->name('admin.inventario.materias.update');


    Route::post('/admin/inventario/materias/{materia}/stock', [InventarioController::class, 'updateStock'])
        ->middleware('role:Administrador')
        ->name('admin.inventario.materias.stock');


    Route::post('/admin/inventario/recetas', [InventarioController::class, 'addIngrediente'])
        ->middleware('role:Administrador')
        ->name('admin.inventario.recetas.store');


    Route::delete('/admin/inventario/recetas/{receta}', [InventarioController::class, 'destroyIngrediente'])
        ->middleware('role:Administrador')
        ->name('admin.inventario.recetas.destroy');


    /*
    |--------------------------------------------------------------------------
    | Gestión de Platos
    |--------------------------------------------------------------------------
    | Solo Administrador
    |--------------------------------------------------------------------------
    */

    Route::resource('/admin/platos', PlatoController::class)
        ->names('admin.platos')
        ->middleware('role:Administrador');


    Route::post('/admin/platos/{plato}/disponibilidad', [PlatoController::class, 'updateDisponibilidad'])
        ->middleware('role:Administrador')
        ->name('admin.platos.disponibilidad');


    /*
    |--------------------------------------------------------------------------
    | Gestión de Categorías
    |--------------------------------------------------------------------------
    | Solo Administrador
    |--------------------------------------------------------------------------
    */

    Route::resource('/admin/categorias', \App\Http\Controllers\Admin\CategoriaController::class)
        ->names('admin.categorias')
        ->middleware('role:Administrador');


    /*
    |--------------------------------------------------------------------------
    | Reportes
    |--------------------------------------------------------------------------
    | Solo Administrador
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/reportes/ventas', [\App\Http\Controllers\Admin\ReporteController::class, 'ventas'])
        ->middleware('role:Administrador')
        ->name('admin.reportes.ventas');

    Route::get('/admin/reportes/ventas/exportar', [\App\Http\Controllers\Admin\ReporteController::class, 'exportarVentas'])
        ->middleware('role:Administrador')
        ->name('admin.reportes.ventas.exportar');

    Route::get('/admin/reportes/ventas/pdf', [\App\Http\Controllers\Admin\ReporteController::class, 'exportarPdf'])
        ->middleware('role:Administrador')
        ->name('admin.reportes.ventas.pdf');


    /*
    |--------------------------------------------------------------------------
    | Panel de Supervisión en Tiempo Real (HU-08)
    |--------------------------------------------------------------------------
    | Solo Administrador
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/supervision', [\App\Http\Controllers\Admin\ReporteController::class, 'supervision'])
        ->middleware('role:Administrador')
        ->name('admin.supervision');


    /*
    |--------------------------------------------------------------------------
    | Gestión de Pedidos
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | Ver pedidos
    |--------------------------------------------------------------------------
    | Administrador, Mesero y Cocinero
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/pedidos', [PedidoController::class, 'index'])
        ->middleware('role:Administrador,Mesero,Cocinero')
        ->name('admin.pedidos.index');


    /*
    |--------------------------------------------------------------------------
    | Crear pedido
    |--------------------------------------------------------------------------
    | Administrador y Mesero
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/pedidos/create', [PedidoController::class, 'create'])
        ->middleware('role:Administrador,Mesero')
        ->name('admin.pedidos.create');


    /*
    |--------------------------------------------------------------------------
    | Guardar pedido
    |--------------------------------------------------------------------------
    | Administrador y Mesero
    |--------------------------------------------------------------------------
    */

    Route::post('/admin/pedidos', [PedidoController::class, 'store'])
        ->middleware('role:Administrador,Mesero')
        ->name('admin.pedidos.store');


    /*
    |--------------------------------------------------------------------------
    | Cambiar estado del pedido
    |--------------------------------------------------------------------------
    | Administrador, Mesero y Cocinero
    |--------------------------------------------------------------------------
    */

    Route::put('/admin/pedidos/{pedido}/estado', [PedidoController::class, 'updateEstado'])
        ->middleware('role:Administrador,Mesero,Cocinero')
        ->name('admin.pedidos.estado');

    /*
    |--------------------------------------------------------------------------
    | Reversión de estados (HU-19)
    |--------------------------------------------------------------------------
    */

    Route::post('/admin/pedidos/{pedido}/revertir', [PedidoController::class, 'revertirEstado'])
        ->middleware('role:Administrador')
        ->name('admin.pedidos.revertir-estado');

    /*
    |--------------------------------------------------------------------------
    | Priorización de pedidos (HU-21)
    |--------------------------------------------------------------------------
    */

    Route::patch('/admin/pedidos/{pedido}/prioridad', [PedidoController::class, 'actualizarPrioridad'])
        ->middleware('role:Administrador,Mesero')
        ->name('admin.pedidos.actualizar-prioridad');

    /*
    |--------------------------------------------------------------------------
    | Cancelación de pedidos
    |--------------------------------------------------------------------------
    */

    Route::post('/admin/pedidos/{pedido}/cancelar', [PedidoController::class, 'cancelarPedido'])
        ->middleware('role:Administrador,Mesero')
        ->name('admin.pedidos.cancelar');


    /*
    |--------------------------------------------------------------------------
    | Agregar item a un pedido
    |--------------------------------------------------------------------------
    | Administrador y Mesero
    |--------------------------------------------------------------------------
    */

    Route::post('/admin/pedidos/{pedido}/agregar-item', [PedidoController::class, 'addItem'])
        ->middleware('role:Administrador,Mesero')
        ->name('admin.pedidos.addItem');


    /*
    |--------------------------------------------------------------------------
    | Ver detalle del pedido
    |--------------------------------------------------------------------------
    | Administrador, Mesero y Cocinero
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/pedidos/{pedido}', [PedidoController::class, 'show'])
        ->middleware('role:Administrador,Mesero,Cocinero')
        ->name('admin.pedidos.show');


    /*
    |--------------------------------------------------------------------------
    | Gestión de Pagos
    |--------------------------------------------------------------------------
    | Administrador y Mesero
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | Mostrar formulario de pago
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/pedidos/{pedido}/pago/create', [PagoController::class, 'create'])
        ->middleware('role:Administrador,Mesero')
        ->name('admin.pedidos.pago.create');


    /*
    |--------------------------------------------------------------------------
    | Registrar pago
    |--------------------------------------------------------------------------
    */

    Route::post('/admin/pedidos/{pedido}/pago', [PagoController::class, 'store'])
        ->middleware('role:Administrador,Mesero')
        ->name('admin.pedidos.pago.store');


    /*
    |--------------------------------------------------------------------------
    | Ver factura del pedido
    |--------------------------------------------------------------------------
    */

    Route::get('/mesero/factura/{pedido}', [PagoController::class, 'factura'])
        ->middleware('role:Mesero')
        ->name('mesero.factura');


    /*
    |--------------------------------------------------------------------------
    | División de cuenta (HU-16)
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/pedidos/{pedido}/dividir-cuenta', [PagoController::class, 'dividirCuenta'])
        ->middleware('role:Administrador,Mesero')
        ->name('admin.pedidos.dividir-cuenta');

    Route::post('/admin/pedidos/{pedido}/procesar-division', [PagoController::class, 'procesarDivision'])
        ->middleware('role:Administrador,Mesero')
        ->name('admin.pedidos.procesar-division');

});


/*
|--------------------------------------------------------------------------
| Verificar sesión activa
|--------------------------------------------------------------------------
*/
Route::get('/check-session', function () {
    if (auth()->check()) {
        return response()->json(['authenticated' => true], 200);
    }
    return response()->json(['authenticated' => false], 401);
});
