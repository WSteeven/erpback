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
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\HorasExtrasSubTipoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\HorasExtrasTipoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\LicenciaEmpleadoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\PeriodoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\PermisoEmpleadoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\PrestamoEmpresarialController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\SolicitudPrestamoEmpresarialController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\TipoLicenciaController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\VacacionController;
use App\Http\Controllers\RecursosHumanos\RubroController;
use App\Models\RecursosHumanos\NominaPrestamos\LicenciaEmpleado;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoHipotecario;
use App\Models\RecursosHumanos\NominaPrestamos\Vacacion;
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
        'prestamo_hipotecario' => PrestamoHipotecarioController::class,
        'prestamo_quirorafario' => PrestamoQuirirafarioController::class,
        'extension_covertura_salud' => ExtensionCoverturaSaludController::class,
        'prestamo_empresarial' => PrestamoEmpresarialController::class,
        'rubro'=>RubroController::class,
        'solicitud_prestamo_empresarial' => SolicitudPrestamoEmpresarialController::class,
        'periodo' => PeriodoController::class,
        'tipo_licencia' => TipoLicenciaController::class,
        'licencia_empleado' => LicenciaEmpleadoController::class,
        'vacacion' => VacacionController::class
    ],
    [
        'parameters' => [],
    ]
);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('datos_empleado/{id}', [EmpleadoController::class, 'datos_empleado']);
    Route::get('prestamos_hipotecario_empleado', [PrestamoHipotecarioController::class, 'prestamos_hipotecario_empleado']);
    Route::put('aprobar_prestamo_empresarial', [SolicitudPrestamoEmpresarialController::class, 'aprobar_prestamo_empresarial']);
    Route::put('rechazar_prestamo_empresarial', [SolicitudPrestamoEmpresarialController::class, 'rechazar_prestamo_empresarial']);
    Route::get('prestamos_quirorafario_empleado', [PrestamoQuirirafarioController::class, 'prestamos_quirorafario_empleado']);
    Route::get('extension_covertura_salud_empleado', [ExtensionCoverturaSaludController::class, 'extension_covertura_salud_empleado']);
    Route::get('sueldo_basico', [RubroController::class, 'sueldo_basico']);
    Route::post('archivo_permiso_empleado', [PermisoEmpleadoController::class, 'archivo_permiso_empleado']);
    Route::post('archivo_licencia_empleado', [LicenciaEmpleadoController::class, 'archivo_licencia_empleado']);
    Route::get('archivo_permiso_empleado', [PermisoEmpleadoController::class, 'index_archivo_permiso_empleado']);
    Route::get('archivo_licencia_empleado', [LicenciaEmpleadoController::class, 'index_archivo_licencia_empleado']);
    Route::post('archivo_prestamo_hipotecario', [PrestamoHipotecarioController::class, 'archivo_prestamo_hipotecario']);
    Route::post('archivo_prestamo_quirorafario', [PrestamoQuirirafarioController::class, 'archivo_prestamo_quirorafario']);

    Route::get('nivel_endeudamiento', [RolPagosController::class, 'nivel_endeudamiento']);
    Route::get ('descuentos_permiso', [VacacionController::class, 'descuentos_permiso']);
});
