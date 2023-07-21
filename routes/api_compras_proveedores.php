<?php

use App\Http\Controllers\CalificacionDepartamentoProveedorController;
use App\Http\Controllers\ContactoProveedorController;
use App\Http\Controllers\CriterioCalificacionController;
use App\Http\Controllers\DetalleDepartamentoProveedorController;
use App\Http\Controllers\OrdenCompraController;
use App\Models\OfertaProveedor;
use Illuminate\Support\Facades\Route;

Route::apiResources([
    'calificaciones-proveedores' => CalificacionDepartamentoProveedorController::class,
    'contactos-proveedores' => ContactoProveedorController::class,
    'criterios-calificaciones' => CriterioCalificacionController::class,
    'detalles-departamentos-proveedor' => DetalleDepartamentoProveedorController::class,
    'ordenes-compras'=>OrdenCompraController::class,
], [
    'parameters' => [
        'contactos-proveedores' => 'contacto',
        'criterios-calificaciones' => 'criterio',
        'calificaciones-proveedores' => 'calificacion',
        'detalles-departamentos-proveedor' => 'detalle',
        'ordenes-compras' => 'orden',
    ],
    'middleware' => ['auth:sanctum']
]);
Route::get('ofertas-proveedores', fn () => ['results' => OfertaProveedor::all()]);
Route::get('log-contactos-proveedores', [ContactoProveedorController::class, 'auditoria']);
