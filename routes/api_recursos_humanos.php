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
use App\Http\Controllers\FondosRotativos\Saldo\SaldoGrupoController;
use App\Http\Controllers\RecursosHumanos\AreasController;
use App\Http\Controllers\RecursosHumanos\BancoController;
use App\Http\Controllers\RecursosHumanos\EstadoCivilController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\EstadoPermisoEmpleadoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\FamiliaresControler;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\HorasExtrasSubTipoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\HorasExtrasTipoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\LicenciaEmpleadoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\MotivoPermisoEmpleadoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\PeriodoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\PermisoEmpleadoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\PrestamoEmpresarialController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\RolPagoMesController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\SolicitudPrestamoEmpresarialController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\TipoLicenciaController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\VacacionController;
use App\Http\Controllers\RecursosHumanos\RubroController;
use App\Http\Controllers\RecursosHumanos\TipoContratoController;
use Illuminate\Support\Facades\Route;

// Generar GET - POST - PUT - DELETE
Route::apiResources(
    [
        'departamentos' => DepartamentoController::class,
        'rol-pagos' => RolPagosController::class,
        'rol_pago_mes' => RolPagoMesController::class,
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
        'rubro' => RubroController::class,
        'solicitud_prestamo_empresarial' => SolicitudPrestamoEmpresarialController::class,
        'periodo' => PeriodoController::class,
        'tipo_licencia' => TipoLicenciaController::class,
        'licencia_empleado' => LicenciaEmpleadoController::class,
        'vacacion' => VacacionController::class,
        'estado_civil' => EstadoCivilController::class,
        'areas' => AreasController::class,
        'familiares' => FamiliaresControler::class,
        'banco' => BancoController::class,
        'motivo_permiso_empleado' => MotivoPermisoEmpleadoController::class,
        'permiso_empleado' => PermisoEmpleadoController::class,
        'estado_permiso_empleado' => EstadoPermisoEmpleadoController::class,
        'tipo_contrato' => TipoContratoController::class,
        'rol_pago' => RolPagosController::class,
    ],
    [
        'parameters' => [],
    ]
);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('datos_empleado/{id}', [EmpleadoController::class, 'datos_empleado']);
    Route::post('rol_pago/estado/{rolPagoId}', [RolPagosController::class, 'cambiar_estado']);
    Route::post('rol_pago/actualizar_masivo', [RolPagosController::class, 'actualizar_masivo']);
    Route::post('rol_pago/finalizar_masivo', [RolPagosController::class, 'finalizar_masivo']);
    Route::get('prestamos_hipotecario_empleado', [PrestamoHipotecarioController::class, 'prestamos_hipotecario_empleado']);
    Route::put('aprobar_prestamo_empresarial', [SolicitudPrestamoEmpresarialController::class, 'aprobar_prestamo_empresarial']);
    Route::put('rechazar_prestamo_empresarial', [SolicitudPrestamoEmpresarialController::class, 'rechazar_prestamo_empresarial']);
    Route::get('prestamos_quirorafario_empleado', [PrestamoQuirirafarioController::class, 'prestamos_quirorafario_empleado']);
    Route::get('extension_covertura_salud_empleado', [ExtensionCoverturaSaludController::class, 'extension_covertura_salud_empleado']);
    Route::get('sueldo_basico', [RubroController::class, 'sueldo_basico']);
    Route::get('porcentaje_iess', [RubroController::class, 'porcentaje_iess']);
    Route::get('porcentaje_anticipo', [RubroController::class, 'porcentaje_anticipo']);

    Route::post('archivo_permiso_empleado', [PermisoEmpleadoController::class, 'archivo_permiso_empleado']);
    Route::post('archivo_licencia_empleado', [LicenciaEmpleadoController::class, 'archivo_licencia_empleado']);
    Route::get('archivo_permiso_empleado', [PermisoEmpleadoController::class, 'index_archivo_permiso_empleado']);
    Route::get('archivo_licencia_empleado', [LicenciaEmpleadoController::class, 'index_archivo_licencia_empleado']);
    Route::get('archivo_rol_pago', [RolPagosController::class, 'index_archivo_rol_pago_empleado']);
    Route::post('archivo_prestamo_hipotecario', [PrestamoHipotecarioController::class, 'archivo_prestamo_hipotecario']);
    Route::post('archivo_prestamo_quirorafario', [PrestamoQuirirafarioController::class, 'archivo_prestamo_quirorafario']);
    Route::post('archivo_rol_pago', [RolPagosController::class, 'archivo_rol_pago_empleado']);
    Route::post('archivo_extencion_conyugal', [ExtensionCoverturaSaludController::class, 'archivo_extension_conyugal']);
    Route::get('nivel_endeudamiento', [RolPagosController::class, 'nivel_endeudamiento']);
    Route::get('descuentos_permiso', [VacacionController::class, 'descuentos_permiso']);
    Route::get('permisos_sin_recuperar', [PermisoEmpleadoController::class, 'permisos_sin_recuperar']);
    Route::get('obtener_prestamo_empleado', [PrestamoEmpresarialController::class, 'obtener_prestamo_empleado']);
    Route::get('otener_saldo_empleado_mes', [SaldoGrupoController::class, 'otener_saldo_empleado_mes']);
    Route::get('imprimir_rol_pago/{rolPagoId}', [RolPagosController::class, 'imprimir_rol_pago']);
    Route::get('imprimir_rol_pago_general/{rolPagoId}', [RolPagoMesController::class, 'imprimir_rol_pago_general']);
    Route::get('verificar-todos_roles-finalizadas', [RolPagoMesController::class, 'verificarTodasRolesFinalizadas']);
    Route::get('finalizar-rol-pago', [RolPagoMesController::class, 'FinalizarRolPago']);
    Route::get('imprimir_reporte_general/{rolPagoId}', [RolPagoMesController::class, 'imprimir_reporte_general']);
    Route::get('enviar-roles-pago/{rolPagoId}',[RolPagoMesController::class, 'enviarRoles']);
});
