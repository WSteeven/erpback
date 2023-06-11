<?php

use App\Http\Controllers\ContactoProveedorController;
use App\Http\Controllers\CriterioCalificacionController;
use App\Models\OfertaProveedor;
use Illuminate\Support\Facades\Route;

Route::apiResources([
    'criterios-calificaciones' => CriterioCalificacionController::class,
    'contactos-proveedores' => ContactoProveedorController::class,
], [
    'parameters' => [
        'contactos-proveedores' => 'contacto',
        'criterios-calificaciones' => 'criterio',
    ],
    'middleware' => ['auth:sanctum']
]);
Route::get('ofertas-proveedores', fn () => ['results' => OfertaProveedor::all()]);
Route::get('log-contactos-proveedores', [ContactoProveedorController::class, 'auditoria']);
