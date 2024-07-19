<?php

use App\Http\Controllers\TransaccionBodegaEgresoController;
use App\Http\Controllers\TransaccionBodegaIngresoController;
use Illuminate\Support\Facades\Route;



// Route::get('dado', fn($request) => response()->json(['mensaje' => 'saludo']));
// Route::get('dado', fn() => response()->json(['mensaje' => 'saludo']));
//    get('/user', fn (Request $request) => new UserInfoResource($request->user()));
Route::get('egresos', [TransaccionBodegaEgresoController::class, 'obtenerEgresos']);
Route::get('ingresos', [TransaccionBodegaIngresoController::class, 'obtenerIngresos']);
// select * from audits where created_at BETWEEN '2024-05-01' and '2024-07-18' and `auditable_type` like '%PreingresoMaterial';