<?php

use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\ConceptoIngresoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\DescuentosGeneralesController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\DescuentosLeyController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\MultaController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\RolPagosController;
use Illuminate\Support\Facades\Route;

Route::apiResources(
    [
        'rol-pagos' => RolPagosController::class,
        'concepto_ingreso' => ConceptoIngresoController::class,
        'descuentos_generales' => DescuentosGeneralesController::class,
        'descuentos_ley' => DescuentosLeyController::class,
        'multa' => MultaController::class
    ],
    [
        'parameters' => [],
    ]
);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('salario/{id}', [EmpleadoController::class,'salario']);
});
