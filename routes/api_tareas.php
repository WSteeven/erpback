<?php

use App\Http\Controllers\ActividadRealizadaSeguimientoSubtareaController;
use App\Http\Controllers\ArchivoSeguimientoController;
use App\Http\Controllers\TransaccionBodegaEgresoController;
use App\Http\Controllers\ReporteControlMaterialController;
use App\Http\Controllers\ControlAsistenciaController;
use App\Http\Controllers\RegistroTendidoController;
use App\Http\Controllers\TrabajoAsignadoController;
use App\Http\Controllers\ArchivoSubtareaController;
use App\Http\Controllers\CausaIntervencionController;
use App\Http\Controllers\ControlCambioController;
use App\Http\Controllers\TipoElementoController;
use App\Http\Controllers\ClienteFinalController;
use App\Http\Controllers\DashboardTareaController;
use App\Http\Controllers\TipoTrabajoController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\MotivoPausaController;
use App\Http\Controllers\MotivoSuspendidoController;
use App\Http\Controllers\MovilizacionSubtareaController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\ReporteModuloTareaController;
use App\Http\Controllers\RutaTareaController;
use App\Http\Controllers\SeguimientoSubtareaController;
use App\Http\Controllers\SubtareaController;
use App\Http\Controllers\TendidoController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\Tareas\CentroCostoController;
use App\Http\Controllers\Tareas\SubCentroCostoController;
use Illuminate\Support\Facades\Route;

// Generar GET - POST - PUT - DELETE
Route::apiResources(
    [
        'subcentros-costos' =>SubCentroCostoController::class,
        'centros-costos' =>CentroCostoController::class,
        'tareas' => TareaController::class,
        'subtareas' => SubtareaController::class,
        'tipos-trabajos' => TipoTrabajoController::class,
        'causas-intervenciones' => CausaIntervencionController::class,
        'rutas-tareas' => RutaTareaController::class,
        'control-asistencias' => ControlAsistenciaController::class,
        'control-cambios' => ControlCambioController::class,
        'tipos-elementos' => TipoElementoController::class,
        'clientes-finales' => ClienteFinalController::class,
        'proyectos' => ProyectoController::class,
        'archivos-subtareas' => ArchivoSubtareaController::class,
        'registros-tendidos' => RegistroTendidoController::class,
        'motivos-pausas' => MotivoPausaController::class,
        'motivos-suspendidos' => MotivoSuspendidoController::class,
        'movilizacion-subtarea' => MovilizacionSubtareaController::class,
        'seguimientos' => SeguimientoSubtareaController::class,
        'archivos-seguimientos' => ArchivoSeguimientoController::class,
        'actividades-realizadas-seguimientos-subtareas' => ActividadRealizadaSeguimientoSubtareaController::class,
    ],
    [
        'parameters' => [
            'subcentros-costos' => 'subcentro',
            'centros-costos' => 'centro',
            'tipos-trabajos' => 'tipo_trabajo',
            'causas-intervenciones' => 'causa_intervencion',
            'tipos-elementos' => 'tipo_elemento',
            'clientes-finales' => 'cliente_final',
            'archivos-subtareas' => 'archivo_subtarea',
            'registros-tendidos' => 'registro_tendido',
            'motivos-pausas' => 'motivo_pausa',
            'motivos-suspendidos' => 'motivo_suspendido',
            'rutas-tareas' => 'ruta_tarea',
            'archivos-seguimientos' => 'archivo_seguimiento',
            'actividades-realizadas-seguimientos-subtareas' => 'actividad_realizada',
        ],
    ]
);

// Modificar los estados de las subtareas
Route::prefix('subtareas')->group(function () {
    Route::post('agendar/{subtarea}', [SubtareaController::class, 'agendar']);
    Route::post('asignar/{subtarea}', [SubtareaController::class, 'asignar']);
    Route::post('ejecutar/{subtarea}', [SubtareaController::class, 'ejecutar']);
    Route::post('realizar/{subtarea}', [SubtareaController::class, 'realizar']);
    Route::post('finalizar/{subtarea}', [SubtareaController::class, 'finalizar']);
    Route::post('pausar/{subtarea}', [SubtareaController::class, 'pausar']);
    Route::post('reanudar/{subtarea}', [SubtareaController::class, 'reanudar']);
    Route::post('corregir/{subtarea}', [SubtareaController::class, 'corregir']);
    Route::post('pendiente/{subtarea}', [SubtareaController::class, 'marcarComoPendiente']);
    Route::post('suspender/{subtarea}', [SubtareaController::class, 'suspender']);
    Route::post('cancelar/{subtarea}', [SubtareaController::class, 'cancelar']);
    Route::get('obtener-pausas/{subtarea}', [SubtareaController::class, 'obtenerPausas']);
    Route::get('obtener-suspendidos/{subtarea}', [SubtareaController::class, 'obtenerSuspendidos']);
    Route::put('actualizar-fechas-reagendar/{subtarea}', [SubtareaController::class, 'actualizarFechasReagendar']);
});

//Centros de costos
Route::post('centros-costos/desactivar/{centro}', [CentroCostoController::class, 'desactivar']);

// Verificar que se pueden finalizar las subtareas
Route::get('verificar-todas-subtareas-finalizadas', [TareaController::class, 'verificarTodasSubtareasFinalizadas']);
Route::get('verificar-material-tarea-devuelto', [TareaController::class, 'verificarMaterialTareaDevuelto']);

// Transferir mis tareas activas
Route::post('transferir-mis-tareas-activas', [TareaController::class, 'transferirMisTareasActivas']);

Route::get('export-seguimiento/{subtarea}', [SeguimientoSubtareaController::class, 'exportarSeguimiento']);
Route::get('ver-seguimiento/{subtarea}', [SeguimientoSubtareaController::class, 'verSeguimiento']);

// Obtener los trabajos designados: de un grupo o empleado individual
Route::get('trabajo-asignado', [TrabajoAsignadoController::class, 'index']);

// Designación de rol lider de grupo durante la creacion de la subtarea
Route::put('designar-lider-grupo/{empleado}', [EmpleadoController::class, 'designarLiderGrupo']);

// GET - POST - PUT del inicio de un tendido de FO (No son los registros)
Route::apiResource('tendidos', TendidoController::class)->except('show');

// Obtiene un registro de tendido filtrado por subtarea // REVISAR SI QUEDA, se toma como reemplazo en el show de arriba
Route::get('tendidos/{subtarea}', [TendidoController::class, 'show']);

// Reportes de material
// Route::get('reportes-control-materiales', [ReporteControlMaterialController::class, 'index']);

Route::get('movilizacion-subtarea-destino-actual', [MovilizacionSubtareaController::class, 'destinoActual']);

/*************
 * Materiales
 *************/
// Obtener los materiales del stock personal
Route::get('materiales-empleado', [TransaccionBodegaEgresoController::class, 'obtenerMaterialesEmpleado']);
// Obtener los materiales para tareas asignados a un empleado
Route::get('materiales-empleado-tarea', [TransaccionBodegaEgresoController::class, 'obtenerMaterialesEmpleadoTarea']);
// Obtener los materiales del empleado tanto de tarea como stock personal
Route::get('materiales-empleado-consolidado', [TransaccionBodegaEgresoController::class, 'obtenerMaterialesEmpleadoConsolidado']);

// Historial de materiales
Route::get('obtener-fechas-historial-materiales-usados/{subtarea}', [SeguimientoSubtareaController::class, 'obtenerFechasHistorialMaterialesUsados']);
Route::get('obtener-fechas-historial-materiales-stock-usados/{subtarea}', [SeguimientoSubtareaController::class, 'obtenerFechasHistorialMaterialesStockUsados']);
Route::get('obtener-historial-material-tarea-usado-por-fecha', [SeguimientoSubtareaController::class, 'obtenerHistorialMaterialTareaUsadoPorFecha']);
Route::get('obtener-historial-material-stock-usado-por-fecha', [SeguimientoSubtareaController::class, 'obtenerHistorialMaterialStockUsadoPorFecha']);
Route::post('actualizar-cantidad-utilizada-historial', [SeguimientoSubtareaController::class, 'actualizarCantidadUtilizadaHistorial']);
Route::post('actualizar-cantidad-utilizada-historial-stock', [SeguimientoSubtareaController::class, 'actualizarCantidadUtilizadaHistorialStock']);

// Editar cantidad utilizada el dia actual
Route::post('actualizar-cantidad-utilizada-tarea', [SeguimientoSubtareaController::class, 'actualizarCantidadUtilizadaMaterialTarea']);
Route::post('actualizar-cantidad-utilizada-stock', [SeguimientoSubtareaController::class, 'actualizarCantidadUtilizadaMaterialStock']);

// Clientes dueños de materiales
Route::get('obtener-clientes-materiales-empleado/{empleado}', [SeguimientoSubtareaController::class, 'obtenerClientesMaterialesEmpleado']);
Route::get('obtener-clientes-materiales-tarea/{empleado}', [SeguimientoSubtareaController::class, 'obtenerClientesMaterialesTarea']);

/***********
 * Reportes
 ***********/
Route::get('reportes', [ReporteModuloTareaController::class, 'index']);

/***********
 * Dashboard
 ***********/
Route::get('dashboard', [DashboardTareaController::class, 'index']);
