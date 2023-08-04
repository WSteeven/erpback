<?php

use App\Http\Controllers\ComprasProveedores\CalificacionDepartamentoProveedorController;
use App\Http\Controllers\ComprasProveedores\ContactoProveedorController;
use App\Http\Controllers\ComprasProveedores\CriterioCalificacionController;
use App\Http\Controllers\ComprasProveedores\DetalleDepartamentoProveedorController;
use App\Http\Controllers\ComprasProveedores\OrdenCompraController;
use App\Http\Controllers\ComprasProveedores\PreordenCompraController;
use App\Models\OfertaProveedor;
use Illuminate\Support\Facades\Route;

Route::apiResources([
    'calificaciones-proveedores' => CalificacionDepartamentoProveedorController::class,
    'contactos-proveedores' => ContactoProveedorController::class,
    'criterios-calificaciones' => CriterioCalificacionController::class,
    'detalles-departamentos-proveedor' => DetalleDepartamentoProveedorController::class,
    'ordenes-compras'=>OrdenCompraController::class,
    'preordenes-compras'=>PreordenCompraController::class,
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
Route::get('ofertas-proveedores', fn () => ['results' => OfertaProveedor::all()]);
Route::get('log-contactos-proveedores', [ContactoProveedorController::class, 'auditoria']);
