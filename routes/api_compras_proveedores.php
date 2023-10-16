<?php

use App\Http\Controllers\ComprasProveedores\CalificacionDepartamentoProveedorController;
// use App\Http\Controllers\ComprasProveedores\CalificacionProveedorController;
use App\Http\Controllers\ComprasProveedores\CategoriaOfertaProveedorController;
use App\Http\Controllers\ComprasProveedores\ContactoProveedorController;
use App\Http\Controllers\ComprasProveedores\CriterioCalificacionController;
use App\Http\Controllers\ComprasProveedores\DatoBancarioProveedorController;
use App\Http\Controllers\ComprasProveedores\DetalleDepartamentoProveedorController;
use App\Http\Controllers\ComprasProveedores\NovedadOrdenCompraController;
use App\Http\Controllers\ComprasProveedores\OrdenCompraController;
use App\Http\Controllers\ComprasProveedores\PrefacturaController;
use App\Http\Controllers\ComprasProveedores\PreordenCompraController;
use App\Http\Controllers\ComprasProveedores\ProformaController;
use App\Models\ComprasProveedores\OfertaProveedor;
use Illuminate\Support\Facades\Route;

Route::apiResources([
    'datos-bancarios-proveedores' => DatoBancarioProveedorController::class,
    'calificaciones-proveedores' => CalificacionDepartamentoProveedorController::class,
    // 'proveedores-calificables' => CalificacionProveedorController::class,
    'categorias-ofertas' => CategoriaOfertaProveedorController::class,
    'contactos-proveedores' => ContactoProveedorController::class,
    'criterios-calificaciones' => CriterioCalificacionController::class,
    'detalles-departamentos-proveedor' => DetalleDepartamentoProveedorController::class,
    'novedades-ordenes-compras' => NovedadOrdenCompraController::class,
    'ordenes-compras' => OrdenCompraController::class,
    'preordenes-compras' => PreordenCompraController::class,
    'proformas' => ProformaController::class,
    'prefacturas' => PrefacturaController::class,
], [
    'parameters' => [
        'contactos-proveedores' => 'contacto',
        'categorias-ofertas' => 'categoria',
        'criterios-calificaciones' => 'criterio',
        'proveedores-calificables' => 'proveedor',
        'calificaciones-proveedores' => 'calificacion',
        'datos-bancarios-proveedores' => 'dato',
        'detalles-departamentos-proveedor' => 'detalle',
        'novedades-ordenes-compras' => 'novedad',
        'ordenes-compras' => 'orden',
        'preordenes-compras' => 'preorden',
        'prefacturas' => 'prefactura',
    ],
    'middleware' => ['auth:sanctum']
]);
Route::get('ofertas-proveedores', fn () => ['results' => OfertaProveedor::all()])->middleware('auth:sanctum');
Route::get('log-contactos-proveedores', [ContactoProveedorController::class, 'auditoria'])->middleware('auth:sanctum');

//show-preview
Route::get('preordenes-compras/show-preview/{preorden}', [PreordenCompraController::class, 'showPreview'])->middleware('auth:sanctum');
Route::get('proformas/show-preview/{proforma}', [ProformaController::class, 'showPreview'])->middleware('auth:sanctum');

//anular
Route::post('ordenes-compras/anular/{orden}', [OrdenCompraController::class, 'anular'])->middleware('auth:sanctum');
Route::post('preordenes-compras/anular/{preorden}', [PreordenCompraController::class, 'anular'])->middleware('auth:sanctum');
Route::post('proformas/anular/{proforma}', [ProformaController::class, 'anular'])->middleware('auth:sanctum');
Route::post('prefacturas/anular/{prefactura}', [PrefacturaController::class, 'anular'])->middleware('auth:sanctum');


//imprimir
Route::get('ordenes-compras/imprimir/{orden}', [OrdenCompraController::class, 'imprimir'])->middleware('auth:sanctum');
Route::get('proformas/imprimir/{proforma}', [ProformaController::class, 'imprimir'])->middleware('auth:sanctum');
Route::get('prefacturas/imprimir/{prefactura}', [PrefacturaController::class, 'imprimir'])->middleware('auth:sanctum');

//listar archivos
Route::get('ordenes-compras/files/{orden}', [OrdenCompraController::class, 'indexFiles'])->middleware('auth:sanctum');
Route::get('calificaciones-proveedores/files/{detalle}', [CalificacionDepartamentoProveedorController::class, 'indexFiles'])->middleware('auth:sanctum');
Route::get('detalles-departamentos-proveedor/files/{detalle}', [DetalleDepartamentoProveedorController::class, 'indexFiles'])->middleware('auth:sanctum');
//guardar archivos
Route::post('ordenes-compras/files/{orden}', [OrdenCompraController::class, 'storeFiles'])->middleware('auth:sanctum');
Route::post('calificaciones-proveedores/files/{detalle}', [CalificacionDepartamentoProveedorController::class, 'storeFiles'])->middleware('auth:sanctum');


//enviar pdfs
Route::get('ordenes-compras/toProveedor/{orden}', [OrdenCompraController::class, 'sendMail'])->middleware('auth:sanctum');
