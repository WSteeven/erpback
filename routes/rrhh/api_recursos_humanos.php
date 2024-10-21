<?php

use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\FondosRotativos\Saldo\SaldoGrupoController;
use App\Http\Controllers\RecursosHumanos\Alimentacion\AlimentacionController;
use App\Http\Controllers\RecursosHumanos\Alimentacion\AsignarAlimentacionController;
use App\Http\Controllers\RecursosHumanos\Alimentacion\DetalleAlimentacionController;
use App\Http\Controllers\RecursosHumanos\AreasController;
use App\Http\Controllers\RecursosHumanos\BancoController;
use App\Http\Controllers\RecursosHumanos\DiscapacidadUsuarioController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\ConceptoIngresoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\DescuentosGeneralesController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\DescuentosLeyController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\EgresoRolPagoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\EstadoPermisoEmpleadoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\ExtensionCoverturaSaludController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\FamiliaresControler;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\HorasExtrasSubTipoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\HorasExtrasTipoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\IngresoRolPagoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\LicenciaEmpleadoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\MotivoPermisoEmpleadoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\MultaController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\PeriodoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\PermisoEmpleadoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\PrestamoEmpresarialController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\PrestamoHipotecarioController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\PrestamoQuirografarioController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\RolPagoMesController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\RolPagosController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\SolicitudPrestamoEmpresarialController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\TipoLicenciaController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\SolicitudVacacionController;
use App\Http\Controllers\RecursosHumanos\RubroController;
use App\Http\Controllers\RecursosHumanos\SeleccionContratacion\PostulanteController;
use App\Http\Controllers\RecursosHumanos\SeleccionContratacion\TipoPuestoController;
use App\Http\Controllers\RecursosHumanos\TipoContratoController;
use App\Http\Controllers\RecursosHumanos\TipoDiscapacidadController;
use App\Http\Controllers\RecursosHumanos\VacacionController;
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
        'prestamos_quirografarios' => PrestamoQuirografarioController::class,
        'extension_cobertura_salud' => ExtensionCoverturaSaludController::class,
        'prestamo_empresarial' => PrestamoEmpresarialController::class,
        'rubros' => RubroController::class,
        'solicitud_prestamo_empresarial' => SolicitudPrestamoEmpresarialController::class,
        'periodo' => PeriodoController::class,
        'tipo_licencia' => TipoLicenciaController::class,
        'licencia_empleado' => LicenciaEmpleadoController::class,
        'solicitudes-vacaciones' => SolicitudVacacionController::class,
        'vacaciones' => VacacionController::class,
        'areas' => AreasController::class,
        'familiares' => FamiliaresControler::class,
        'banco' => BancoController::class,
        'motivo_permiso_empleado' => MotivoPermisoEmpleadoController::class,
        'permiso_empleado' => PermisoEmpleadoController::class,
        'estado_permiso_empleado' => EstadoPermisoEmpleadoController::class,
        'tipo_contrato' => TipoContratoController::class,
        'rol_pago' => RolPagosController::class,
        'egreso_rol_pago' => EgresoRolPagoController::class,
        'ingreso_rol_pago' => IngresoRolPagoController::class,
        /*******************************
         *  Módulo de Alimentacion
         ******************************/
        'asignar-alimentacion' => AsignarAlimentacionController::class,
        'alimentacion' => AlimentacionController::class,
        'detalle-alimentacion' => DetalleAlimentacionController::class,

        'tipos_puestos_trabajos' => TipoPuestoController::class,
        'tipos-discapacidades' => TipoDiscapacidadController::class,
    ],
    [
        'parameters' => [
            'descuentos_generales' => 'descuento_general',
            'extension_cobertura_salud' => 'extension',
            'descuentos_ley' => 'descuento_ley',
            'prestamo_empresarial' => 'prestamo',
            'prestamos_quirografarios' => 'prestamo',
            'licencia_empleado'=>'licencia',
            'permiso_empleado'=>'permiso',
            'tipos_puestos_trabajos' => 'tipo_puesto_trabajo',
            'solicitudes-vacaciones' => 'vacacion',
            'vacaciones' => 'vacacion',
            'rol_pago_mes'=>'rol',
            'tipo_licencia'=>'tipo',
        ],

    ]
);
Route::get('discapacidades-usuario', [DiscapacidadUsuarioController::class, 'discapacidadesUsuario']);
Route::post('registro', [PostulanteController::class, 'store'])->withoutMiddleware(['auth:sanctum']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('datos_empleado/{id}', [EmpleadoController::class, 'datos_empleado']);
    Route::post('rol_pago/estado/{rolPagoId}', [RolPagosController::class, 'cambiar_estado']);
    Route::post('rol_pago/actualizar_masivo', [RolPagosController::class, 'actualizar_masivo']);
    Route::post('rol_pago/finalizar_masivo', [RolPagosController::class, 'finalizar_masivo']);
    Route::get('prestamos_hipotecario_empleado', [PrestamoHipotecarioController::class, 'prestamos_hipotecario_empleado']);
    Route::put('aprobar_prestamo_empresarial', [SolicitudPrestamoEmpresarialController::class, 'aprobar_prestamo_empresarial']);
    Route::put('rechazar_prestamo_empresarial', [SolicitudPrestamoEmpresarialController::class, 'rechazar_prestamo_empresarial']);
    Route::get('$prestamos_quirografario_empleado', [PrestamoQuirografarioController::class, 'prestamoQuirografarioEmpleado']);
    Route::get('extension_cobertura_salud_empleado', [ExtensionCoverturaSaludController::class, 'extensionCoberturaSaludEmpleado']);
    Route::get('sueldo_basico', [RubroController::class, 'sueldo_basico']);
    Route::get('porcentaje_iess', [RubroController::class, 'porcentaje_iess']);
    Route::get('porcentaje_anticipo', [RubroController::class, 'porcentaje_anticipo']);
    Route::post('archivo_permiso_empleado', [PermisoEmpleadoController::class, 'archivoPermisoEmpleado']);
    Route::post('archivo_licencia_empleado', [LicenciaEmpleadoController::class, 'archivo_licencia_empleado']);
    Route::get('archivo_permiso_empleado', [PermisoEmpleadoController::class, 'indexArchivoPermisoEmpleado']);
    Route::get('archivo_licencia_empleado', [LicenciaEmpleadoController::class, 'index_archivo_licencia_empleado']);
    Route::get('archivo_rol_pago', [RolPagosController::class, 'index_archivo_rol_pago_empleado']);
    Route::post('archivo_prestamo_hipotecario', [PrestamoHipotecarioController::class, 'archivo_prestamo_hipotecario']);
    Route::post('archivo_prestamo_quirorafario', [PrestamoQuirografarioController::class, 'archivoPrestamoQuirografario']);
    Route::post('archivo_rol_pago', [RolPagosController::class, 'archivo_rol_pago_empleado']);
    Route::post('archivo_extencion_conyugal', [ExtensionCoverturaSaludController::class, 'archivoExtensionConyugal']);
    Route::post('archivo-rol-pago-mes', [RolPagoMesController::class, 'importarRolPago']);
    Route::get('nivel_endeudamiento', [RolPagosController::class, 'nivel_endeudamiento']);
    Route::get('descuentos_permiso', [SolicitudVacacionController::class, 'descuentos_permiso']);
    Route::get('solicitudes-vacaciones/imprimir/{vacacion}', [SolicitudVacacionController::class, 'imprimir']);
    Route::post('solicitudes-vacaciones/reporte', [SolicitudVacacionController::class, 'reporteVacaciones']);
    Route::get('permisos_sin_recuperar', [PermisoEmpleadoController::class, 'permisosSinRecuperar']);
    Route::get('obtener_prestamo_empleado', [PrestamoEmpresarialController::class, 'obtenerPrestamoEmpleado']);
    Route::get('otener_saldo_empleado_mes', [SaldoGrupoController::class, 'otener_saldo_empleado_mes']);
    Route::get('imprimir_rol_pago/{rolPagoId}', [RolPagosController::class, 'imprimir_rol_pago']);
    Route::get('imprimir_rol_pago_general/{rolPagoId}', [RolPagoMesController::class, 'imprimirRolPagoGeneral']);
    Route::get('imprimir_reporte_general_empleado', [EmpleadoController::class, 'imprimir_reporte_general_empleado']);
    Route::get('verificar-todos_roles-finalizadas', [RolPagoMesController::class, 'verificarTodasRolesFinalizadas']);
    Route::get('finalizar-rol-pago', [RolPagoMesController::class, 'finalizarRolPago']);
    Route::get('habilitar-empleado', [EmpleadoController::class, 'HabilitaEmpleado']);
    Route::get('imprimir_reporte_general/{rolPagoId}', [RolPagoMesController::class, 'imprimirReporteGeneral']);
    Route::get('enviar-roles-pago/{rolPagoId}',[RolPagoMesController::class, 'enviarRoles']);
    Route::get('enviar-rol-pago-empleado/{rolPagoId}',[RolPagosController::class, 'enviar_rolPago_empleado']);
    Route::get('crear-cash-roles-pago/{rolPagoId}',[RolPagoMesController::class, 'crearCashRolPago']);
    Route::get('actualizar-rol-pago/{rol}',[RolPagoMesController::class, 'refrescarRolPago']);
    Route::get('agregar-nuevos-empleados/{rol}',[RolPagoMesController::class, 'agregarNuevosEmpleados']);
    Route::get('generar-username',[EmpleadoController::class, 'obtenerNombreUsuario']);
    Route::post('anular-prestamo-empresarial',[PrestamoEmpresarialController::class, 'deshabilitarPrestamo']);
    Route::get('crear-cash-alimentacion/{alimentacion_id}',[AlimentacionController::class, 'crear_cash_alimentacion']);
    Route::get('imprimir-reporte-general-alimentacion/{id}',[AlimentacionController::class, 'reporte_alimentacion']);
    Route::get('finalizar-asignacion-alimentacion', [AlimentacionController::class, 'finalizarAsignacionAlimentacion']);
});
