<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => ['jwt.auth']], function() {
    Route::resource('users', 'UsersController');
    Route::resource('roles', 'RolesController');
    Route::resource('clientes', 'ClientesController');
    Route::resource('compras', 'ComprasController');
    Route::resource('comentarios', 'ComentariosController');
    Route::resource('comisiones', 'ComisionesController');
    Route::resource('compras-detalle', 'ComprasDetalleController');
    Route::resource('configuraciones', 'ConfiguracionController');
    Route::resource('correos', 'CorreosController');
    Route::resource('cuentas', 'CuentasController');
    Route::resource('direcciones', 'DireccionesController');
    Route::resource('empleados', 'EmpleadosController');
    Route::resource('formas-pago', 'FormasPagoController');
    Route::resource('marcas', 'MarcasController');
    Route::resource('intereses', 'InteresesController');
    Route::resource('abonos', 'AbonosController');
    Route::resource('pagos', 'PagosController');
    Route::resource('pasarelas', 'PasarelasController');
    Route::resource('skills', 'SkillsController');
    Route::resource('productos', 'ProductosController');
    Route::resource('tipos-compra', 'TiposCompraController');
    Route::resource('tipos-configuracion', 'TiposConfiguracionController');
    Route::resource('tipos-direccion', 'TiposDireccionController');
    Route::resource('tipos-forma-pago', 'TiposFormaPagoController');
    Route::resource('tipos-venta', 'TiposVentaController');
    Route::resource('ventas', 'VentasController');
    Route::resource('ventas-detalle', 'VentasDetalleController');

    Route::get('filter/{id}/users/{state}', "UsersController@getThisByFilter");
    Route::get('filter/{id}/roles/{state}', "RolesController@getThisByFilter");
    Route::get('filter/{id}/clientes/{state}', "ClientesController@getThisByFilter");
    Route::get('filter/{id}/compras/{state}', "ComprasController@getThisByFilter");
    Route::get('filter/{id}/compras-detalle/{state}', "ComprasDetalleController@getThisByFilter");
    Route::get('filter/{id}/configuraciones/{state}', "ConfiguracionController@getThisByFilter");
    Route::get('filter/{id}/correos/{state}', "CorreosController@getThisByFilter");
    Route::get('filter/{id}/cuentas/{state}', "CuentasController@getThisByFilter");
    Route::get('filter/{id}/direcciones/{state}', "DireccionesController@getThisByFilter");
    Route::get('filter/{id}/empleados/{state}', "EmpleadosController@getThisByFilter");
    Route::get('filter/{id}/formas-pago/{state}', "FormasPagoController@getThisByFilter");
    Route::get('filter/{id}/marcas/{state}', "MarcasController@getThisByFilter");
    Route::get('filter/{id}/abonos/{state}', "AbonosController@getThisByFilter");
    Route::get('filter/{id}/pasarelas/{state}', "PasarelasController@getThisByFilter");
    Route::get('filter/{id}/skills/{state}', "SkillsController@getThisByFilter");
    Route::get('filter/{id}/paquetes/{state}', "PaquetesController@getThisByFilter");
    
    Route::get('filter/{id}/tipos-compra/{state}', "TiposCompraController@getThisByFilter");
    Route::get('filter/{id}/tipos-configuracion/{state}', "TiposConfiguracionController@getThisByFilter");
    Route::get('filter/{id}/tipos-direccion/{state}', "TiposDireccionController@getThisByFilter");
    Route::get('filter/{id}/tipos-forma-pago/{state}', "TiposFormaPagoController@getThisByFilter");
    Route::get('filter/{id}/tipos-ventas/{state}', "TiposVentasController@getThisByFilter");
    Route::get('filter/{id}/ventas-detalle/{state}', "VentasDetalleController@getThisByFilter");
    Route::get('filter/{id}/transacciones/{state}', "TransaccionesController@getThisByFilter");
    Route::get('filter/{id}/tipos-cuentas/{state}', "TiposCuentaController@getThisByFilter");
    Route::get('filter/{id}/bancos/{state}', "BancosController@getThisByFilter");

    Route::post('pago2co', 'PagosController@pago2co');
    Route::post('qpago', 'PagosController@pagarQPP');
    Route::post('pagadito', 'PagosController@pagar');
    Route::post('pagalo', 'PagosController@pagalo');
    Route::post('pagar', 'PagosController@pagar');
    Route::post('comprobante', 'PagosController@comprobanteCompra');

    Route::post('enviar', 'PagosController@enviar');
    Route::post('users/{id}/changepassword', "UsersController@changePassword");
    Route::post('transacciones/{id}/generar', "TransaccionesController@generarTransacciones");
    Route::get('downgrade/{id}', 'AuthenticateController@borrarAvatar');
    Route::put('avatar/{id}', 'AuthenticateController@updateAvatar');
    Route::post('logout', 'AuthenticateController@logout');

    Route::resource('anuncios', 'AnunciosController');
    Route::resource('proveedores', 'ProveedoresController');
    Route::resource('tipos-items', 'TiposItemController');
    Route::resource('categorias', 'CategoriasController');
    Route::resource('productos', 'ProductosController');
    Route::resource('inventarios', 'InventariosController');
    Route::resource('paquetes', 'PaquetesController');
    Route::resource('items', 'ItemsController');
});

Route::get('actualizaTipoCambio', "PasarelasController@actualizarTipoCambio");
Route::get('anuncios', 'AnunciosController@index');
Route::get('proveedores', 'ProveedoresController@index');
Route::get('comisiones', 'ComisionesController@index');

Route::get('filter/{id}/comentarios/{state}', "ComentariosController@getThisByFilter");
Route::get('filter/{id}/comisiones/{state}', "ComisionesController@getThisByFilter");
Route::get('filter/{id}/productos/{state}', "ProductosController@getThisByFilter");
Route::get('filter/{id}/proveedores/{state}', "ProveedoresController@getThisByFilter");
Route::get('filter/{id}/tipos-items/{state}', "CategoriasController@getThisByFilter");
Route::get('filter/{id}/categorias/{state}', "TiposItemController@getThisByFilter");
Route::get('filter/{id}/inventarios/{state}', "InventariosController@getThisByFilter");
Route::get('filter/{id}/items/{state}', "ItemsController@getThisByFilter");
Route::get('filter/{id}/intereses/{state}', "InteresesController@getThisByFilter");
Route::get('filter/{id}/ventas/{state}', "VentasController@getThisByFilter");

Route::get('list', 'PagosController@info');
Route::post('validarCaptcha', 'AuthenticateController@validarCaptcha');
Route::post('ventas', 'VentasController@store');
Route::post('upload', 'AuthenticateController@uploadAvatar');
Route::post('users/password/reset', 'UsersController@recoveryPassword');
Route::post('login', 'AuthenticateController@login');
Route::post('users', 'UsersController@store');
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');