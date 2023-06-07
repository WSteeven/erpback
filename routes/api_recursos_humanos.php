<?php

use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\ConceptoIngresoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\DescuentosGeneralesController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\DescuentosLeyController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\ExtensionCoverturaSaludController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\MultaController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\PrestamoHipotecarioController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\PrestamoQuirirafarioController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\RolPagosController;
use Illuminate\Support\Facades\Route;

Route::apiResources(
    [
        'rol-pagos' => RolPagosController::class,
        'concepto_ingreso' => ConceptoIngresoController::class,
        'descuentos_generales' => DescuentosGeneralesController::class,
        'descuentos_ley' => DescuentosLeyController::class,
        'multa' => MultaController::class,
        'prestamos_hipotecario' => PrestamoHipotecarioController::class,
        'prestamos_quirorafario' => PrestamoQuirirafarioController::class,
        'extension_covertura_salud' => ExtensionCoverturaSaludController::class
    ],
    [
        'parameters' => [],
    ]
);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('datos_empleado/{id}', [EmpleadoController::class,'datos_empleado']);
    Route::get('prestamos_hipotecario_empleado', [PrestamoHipotecarioController::class,'prestamos_hipotecario_empleado']);
    Route::get('prestamos_quirorafario_empleado', [PrestamoQuirirafarioController::class,'prestamos_quirorafario_empleado']);
    Route::get('extension_covertura_salud_empleado', [ExtensionCoverturaSaludController::class,'extension_covertura_salud_empleado']);

});
