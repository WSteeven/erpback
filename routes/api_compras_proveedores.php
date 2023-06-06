<?php

use App\Models\OfertaProveedor;
use Illuminate\Support\Facades\Route;

// Route::apiResources();
Route::get('ofertas_proveedores', fn()=>['results'=>OfertaProveedor::all()]);