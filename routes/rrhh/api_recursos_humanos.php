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
use App\Http\Controllers\RecursosHumanos\EmpleadoDelegadoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\ConceptoIngresoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\CuotaDescuentoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\DescuentoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\DescuentosGeneralesController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\DescuentosLeyController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\DetalleVacacionController;
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
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\PlanVacacionController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\PrestamoEmpresarialController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\PrestamoHipotecarioController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\PrestamoQuirografarioController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\RolPagoMesController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\RolPagoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\SolicitudPrestamoEmpresarialController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\SolicitudVacacionController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\TipoLicenciaController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\VacacionController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\ValorEmpleadoRolMensualController;
use App\Http\Controllers\RecursosHumanos\PlanificadorController;
use App\Http\Controllers\RecursosHumanos\RubroController;
use App\Http\Controllers\RecursosHumanos\SeleccionContratacion\PostulanteController;
use App\Http\Controllers\RecursosHumanos\SeleccionContratacion\TipoPuestoController;
use App\Http\Controllers\RecursosHumanos\TipoContratoController;
use App\Http\Controllers\RecursosHumanos\TipoDiscapacidadController;
use App\Models\RecursosHumanos\NominaPrestamos\PlazoPrestamoEmpresarial;
use Illuminate\Support\Facades\Route;

// Generar GET - POST - PUT - DELETE
Route::apiResources(
    [
        'empleados-delegados' => EmpleadoDelegadoController::class,
        'departamentos' => DepartamentoController::class,
        'rol-pagos' => RolPagoController::class,
        'rol_pago_mes' => RolPagoMesController::class,
        'concepto_ingreso' => ConceptoIngresoController::class,
        'horas-extras-tipo' => HorasExtrasTipoController::class,
        'horas_extras_subtipo' => HorasExtrasSubTipoController::class,
        'descuentos' => DescuentoController::class,
        'descuentos_generales' => DescuentosGeneralesController::class,
        'descuentos_ley' => DescuentosLeyController::class,
        'multa' => MultaController::class,
        'prestamo_hipotecario' => PrestamoHipotecarioController::class,
        'prestamos_quirografarios' => PrestamoQuirografarioController::class,
        'extension_cobertura_salud' => ExtensionCoverturaSaludController::class,
        'prestamo_empresarial' => PrestamoEmpresarialController::class,
        'rubros' => RubroController::class,
        'solicitud-prestamo-empresarial' => SolicitudPrestamoEmpresarialController::class,
        'periodo' => PeriodoController::class,
        'tipo_licencia' => TipoLicenciaController::class,
        'licencia_empleado' => LicenciaEmpleadoController::class,
        'solicitudes-vacaciones' => SolicitudVacacionController::class,
        'vacaciones' => VacacionController::class,
        'detalles-vacaciones' => DetalleVacacionController::class,
        'planes-vacaciones' => PlanVacacionController::class,
        'planificadores' => PlanificadorController::class,
        'areas' => AreasController::class,
        'familiares' => FamiliaresControler::class,
        'banco' => BancoController::class,
        'motivo_permiso_empleado' => MotivoPermisoEmpleadoController::class,
        'permiso_empleado' => PermisoEmpleadoController::class,
        'estado_permiso_empleado' => EstadoPermisoEmpleadoController::class,
        'tipo_contrato' => TipoContratoController::class,
        'rol_pago' => RolPagoController::class,
        'egreso_rol_pago' => EgresoRolPagoController::class,
        'ingreso_rol_pago' => IngresoRolPagoController::class,
        'valores-cargados-roles' => ValorEmpleadoRolMensualController::class,
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
            'planes-vacaciones' => 'plan',
            'planificadores' => 'plan',
            'licencia_empleado' => 'licencia',
            'permiso_empleado' => 'permiso',
            'tipos_puestos_trabajos' => 'tipo_puesto_trabajo',
            'solicitudes-vacaciones' => 'solicitud',
            'solicitud-prestamo-empresarial' => 'solicitud',
            'vacaciones' => 'vacacion',
            'detalles-vacaciones' => 'detalle',
            'rol_pago_mes' => 'rol',
            'tipo_licencia' => 'tipo',
            'valores-cargados-roles' => 'valor',
            'empleados-delegados' => 'delegacion',
        ],

    ]
);
Route::get('discapacidades-usuario', [DiscapacidadUsuarioController::class, 'discapacidadesUsuario']);
Route::post('registro', [PostulanteController::class, 'store'])->withoutMiddleware(['auth:sanctum']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('empleados-delegados/desactivar/{empleado}', [EmpleadoDelegadoController::class, 'desactivar']);
    Route::get('datos_empleado/{id}', [EmpleadoController::class, 'datos_empleado']);
    Route::post('rol_pago/estado/{rol_pago}', [RolPagoController::class, 'cambiarEstado']); // TODO: Revisar, este endpoint no se está usando, revisar por que se crea roles de pago y automatica se ponen en estado CANCELADO
    Route::post('rol_pago/actualizar-masivo', [RolPagoController::class, 'actualizarMasivo']);
    Route::post('rol_pago/finalizar-masivo', [RolPagoController::class, 'finalizarMasivo']);
    Route::get('prestamos_hipotecario_empleado', [PrestamoHipotecarioController::class, 'prestamos_hipotecario_empleado']);
//    Route::put('aprobar_prestamo_empresarial', [SolicitudPrestamoEmpresarialController::class, 'aprobar_prestamo_empresarial']);
//    Route::put('rechazar_prestamo_empresarial', [SolicitudPrestamoEmpresarialController::class, 'rechazar_prestamo_empresarial']);
    Route::get('$prestamos_quirografario_empleado', [PrestamoQuirografarioController::class, 'prestamoQuirografarioEmpleado']);
    Route::get('extension_cobertura_salud_empleado', [ExtensionCoverturaSaludController::class, 'extensionCoberturaSaludEmpleado']);
    Route::get('sueldo_basico', [RubroController::class, 'sueldo_basico']);
    Route::get('porcentaje_iess', [RubroController::class, 'porcentaje_iess']);
    Route::get('porcentaje_anticipo', [RubroController::class, 'porcentaje_anticipo']);
    Route::post('archivo_permiso_empleado', [PermisoEmpleadoController::class, 'archivoPermisoEmpleado']);
    Route::post('archivo_licencia_empleado', [LicenciaEmpleadoController::class, 'archivo_licencia_empleado']);
    Route::get('archivo_permiso_empleado', [PermisoEmpleadoController::class, 'indexArchivoPermisoEmpleado']);
    Route::get('archivo_licencia_empleado', [LicenciaEmpleadoController::class, 'index_archivo_licencia_empleado']);
    Route::get('archivo_rol_pago', [RolPagoController::class, 'index_archivo_rol_pago_empleado']);
    Route::post('archivo_prestamo_hipotecario', [PrestamoHipotecarioController::class, 'archivo_prestamo_hipotecario']);
    Route::post('archivo_prestamo_quirorafario', [PrestamoQuirografarioController::class, 'archivoPrestamoQuirografario']);
    Route::post('archivo_rol_pago', [RolPagoController::class, 'archivo_rol_pago_empleado']);
    Route::post('archivo_extension_conyugal', [ExtensionCoverturaSaludController::class, 'archivoExtensionConyugal']);
//    Route::post('archivo-rol-pago-mes', [RolPagoMesController::class, 'importarRolPago']);
    Route::get('nivel_endeudamiento', [RolPagoController::class, 'nivel_endeudamiento']);
//    Route::get('descuentos_permiso', [SolicitudVacacionController::class, 'descuentos_permiso']);
    Route::get('solicitudes-vacaciones/imprimir/{vacacion}', [SolicitudVacacionController::class, 'imprimir']);
    Route::get('solicitudes-vacaciones/derecho-vacaciones/{id}', [SolicitudVacacionController::class, 'derechoVacaciones']);
    Route::patch('solicitudes-vacaciones/anular/{solicitud}', [SolicitudVacacionController::class, 'anular']);
    Route::get('planificadores/imprimir/{plan}', [PlanificadorController::class, 'imprimir']);
    Route::post('vacaciones/reporte', [VacacionController::class, 'reporteVacaciones']);
    Route::get('permisos_sin_recuperar', [PermisoEmpleadoController::class, 'permisosSinRecuperar']);
    Route::get('obtener_prestamo_empleado', [PrestamoEmpresarialController::class, 'obtenerPrestamoEmpleado']);
    Route::get('otener_saldo_empleado_mes', [SaldoGrupoController::class, 'otener_saldo_empleado_mes']);
    Route::get('imprimir_rol_pago/{rol_pago}', [RolPagoController::class, 'imprimir_rol_pago']);
    Route::get('imprimir_rol_pago_general/{rolPagoId}', [RolPagoMesController::class, 'imprimirRolPagoGeneral']);
    Route::get('imprimir_reporte_general_empleado', [EmpleadoController::class, 'imprimir_reporte_general_empleado']);
    Route::get('verificar-todos-roles-finalizadas', [RolPagoMesController::class, 'verificarTodasRolesFinalizadas']);
    Route::post('activar-rol-pago/{rol}', [RolPagoMesController::class, 'activarRolPago']);
    Route::get('finalizar-rol-pago', [RolPagoMesController::class, 'finalizarRolPago']);
    Route::get('habilitar-empleado', [EmpleadoController::class, 'HabilitaEmpleado']);
    Route::get('imprimir_reporte_general/{rolPagoId}', [RolPagoMesController::class, 'imprimirReporteGeneral']);
    Route::get('enviar-roles-pago/{rolPagoId}', [RolPagoMesController::class, 'enviarRoles']);
    Route::get('enviar-rol-pago-empleado/{rol_pago}', [RolPagoController::class, 'enviarRolPagoEmpleado']);
    Route::post('crear-cash-roles-pago/{rolPagoId}', [RolPagoMesController::class, 'crearCashRolPago']);
    Route::get('actualizar-rol-pago/{rol}', [RolPagoMesController::class, 'refrescarRolPago']);
    Route::get('agregar-nuevos-empleados/{rol}', [RolPagoMesController::class, 'agregarNuevosEmpleados']);
    Route::post('generar-username', [EmpleadoController::class, 'obtenerNombreUsuario']);
    Route::post('anular-prestamo-empresarial', [PrestamoEmpresarialController::class, 'deshabilitarPrestamo']);
    Route::get('actualizar-prestamo-empresarial/{prestamo}', [PrestamoEmpresarialController::class, 'actualizarPrestamo']);
    Route::get('crear-cash-alimentacion/{alimentacion_id}', [AlimentacionController::class, 'crear_cash_alimentacion']);
    Route::get('imprimir-reporte-general-alimentacion/{id}', [AlimentacionController::class, 'reporte_alimentacion']);
    Route::get('finalizar-asignacion-alimentacion', [AlimentacionController::class, 'finalizarAsignacionAlimentacion']);
    Route::post('pagar-prestamos-rol-actual/{rol}', [RolPagoMesController::class, 'pagarPrestamosEmpresariales']);
    Route::post('calcular-cuotas-descuento', [DescuentoController::class, 'calcularCantidadCuotas']);
    Route::post('calcular-cuotas-prestamo-empresarial', [PrestamoEmpresarialController::class, 'calcularCantidadCuotas']);
//    Route::post('recalcular-cuotas-prestamo-empresarial', [PrestamoEmpresarialController::class, 'recalcularValoresCuotas']);
    Route::post('aplazar-cuota-descuento/{cuota}', [CuotaDescuentoController::class, 'aplazarCuotaDescuento']);
    Route::post('aplazar-cuota-prestamo/{cuota}', [PrestamoEmpresarialController::class, 'aplazarCuotaPrestamo']);
    Route::post('pagar-cuota-prestamo/{cuota}', [PrestamoEmpresarialController::class, 'pagarCuotaPrestamo']);
    Route::post('reporte-prestamos-empresariales', [PrestamoEmpresarialController::class, 'reportes']);

    Route::get('empleado-tiene-subordinados/{empleado}', [EmpleadoController::class, 'consultarEmpleadosSubordinados']);
    Route::post('reasignar-empleados-subordinados', [EmpleadoController::class, 'reasignarEmpleadosSubordinados']);
    Route::post('desvincular-empleado', [EmpleadoController::class, 'desvincularEmpleado']);
});
