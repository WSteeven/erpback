<?php

use App\Http\Controllers\ContactoProveedorController;
use App\Models\OfertaProveedor;
use Illuminate\Support\Facades\Route;

Route::apiResources([
    'contactos-proveedores'=>ContactoProveedorController::class,
],[
    'parameters'=>['contactos-proveedores'=>'contacto',],
    'middleware'=>['auth:sanctum']
]);
Route::get('ofertas-proveedores', fn()=>['results'=>OfertaProveedor::all()]);
