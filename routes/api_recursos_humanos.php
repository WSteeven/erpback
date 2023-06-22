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
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\HorasExtrasSubTipoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\HorasExtrasTipoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\PrestamoEmpresarialController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\SolicitudPrestamoEmpresarialController;
use App\Http\Controllers\RecursosHumanos\RubroController;
use App\Models\RecursosHumanos\NominaPrestamos\SolicitudPrestamoEmpresarial;
use Illuminate\Support\Facades\Route;

// Generar GET - POST - PUT - DELETE
Route::apiResources(
    [
        'departamentos' => DepartamentoController::class,
        'rol-pagos' => RolPagosController::class,
        'concepto_ingreso' => ConceptoIngresoController::class,
        'horas_extras_tipo' => HorasExtrasTipoController::class,
        'horas_extras_subtipo' => HorasExtrasSubTipoController::class,
        'descuentos_generales' => DescuentosGeneralesController::class,
        'descuentos_ley' => DescuentosLeyController::class,
        'multa' => MultaController::class,
        'prestamos_hipotecario' => PrestamoHipotecarioController::class,
        'prestamos_quirorafario' => PrestamoQuirirafarioController::class,
        'extension_covertura_salud' => ExtensionCoverturaSaludController::class,
        'prestamo_empresarial' => PrestamoEmpresarialController::class,
        'rubro'=>RubroController::class,
        'solicitud_prestamo_empresarial' => SolicitudPrestamoEmpresarialController::class
    ],
    [
        'parameters' => [],
    ]
);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('datos_empleado/{id}', [EmpleadoController::class, 'datos_empleado']);
    Route::get('prestamos_hipotecario_empleado', [PrestamoHipotecarioController::class, 'prestamos_hipotecario_empleado']);
    Route::put('aprobar_prestamo_empresarial', [SolicitudPrestamoEmpresarialController::class, 'aprobar_prestamo_empresarial']);
    Route::get('prestamos_quirorafario_empleado', [PrestamoQuirirafarioController::class, 'prestamos_quirorafario_empleado']);
    Route::get('extension_covertura_salud_empleado', [ExtensionCoverturaSaludController::class, 'extension_covertura_salud_empleado']);
    Route::get('sueldo_basico', [RubroController::class, 'sueldo_basico']);
});
