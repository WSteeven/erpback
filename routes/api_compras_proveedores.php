<?php

use App\Http\Controllers\ComprasProveedores\CalificacionDepartamentoProveedorController;
use App\Http\Controllers\ComprasProveedores\ContactoProveedorController;
use App\Http\Controllers\ComprasProveedores\CriterioCalificacionController;
use App\Http\Controllers\ComprasProveedores\DetalleDepartamentoProveedorController;
use App\Http\Controllers\ComprasProveedores\OrdenCompraController;
use App\Http\Controllers\ComprasProveedores\PreordenCompraController;
use App\Http\Controllers\ComprasProveedores\ProformaController;
use App\Models\ComprasProveedores\OfertaProveedor;
use Illuminate\Support\Facades\Route;

Route::apiResources([
    'calificaciones-proveedores' => CalificacionDepartamentoProveedorController::class,
    'contactos-proveedores' => ContactoProveedorController::class,
    'criterios-calificaciones' => CriterioCalificacionController::class,
    'detalles-departamentos-proveedor' => DetalleDepartamentoProveedorController::class,
    'ordenes-compras' => OrdenCompraController::class,
    'preordenes-compras' => PreordenCompraController::class,
    'proformas' => ProformaController::class,
], [
    'parameters' => [
        'contactos-proveedores' => 'contacto',
        'criterios-calificaciones' => 'criterio',
        'calificaciones-proveedores' => 'calificacion',
        'detalles-departamentos-proveedor' => 'detalle',
        'ordenes-compras' => 'orden',
        'preordenes-compras' => 'preorden',
    ],
    'middleware' => ['auth:sanctum']
]);
Route::get('ofertas-proveedores', fn () => ['results' => OfertaProveedor::all()])->middleware('auth:sanctum');
Route::get('log-contactos-proveedores', [ContactoProveedorController::class, 'auditoria'])->middleware('auth:sanctum');

//show-preview
Route::get('preordenes-compras/show-preview/{preorden}', [PreordenCompraController::class, 'showPreview'])->middleware('auth:sanctum');

//anular
Route::post('ordenes-compras/anular/{orden}', [OrdenCompraController::class, 'anular'])->middleware('auth:sanctum');
Route::post('preordenes-compras/anular/{preorden}', [PreordenCompraController::class, 'anular'])->middleware('auth:sanctum');
Route::post('proformas/anular/{proforma}', [ProformaController::class, 'anular'])->middleware('auth:sanctum');


//imprimir
Route::get('ordenes-compras/imprimir/{orden}', [OrdenCompraController::class, 'imprimir'])->middleware('auth:sanctum');
Route::get('proformas/imprimir/{proforma}', [ProformaController::class, 'imprimir'])->middleware('auth:sanctum');
