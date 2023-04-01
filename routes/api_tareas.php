<?php

use App\Http\Controllers\TransaccionBodegaEgresoController;
use App\Http\Controllers\ReporteControlMaterialController;
use App\Http\Controllers\ControlAsistenciaController;
use App\Http\Controllers\RegistroTendidoController;
use App\Http\Controllers\TrabajoAsignadoController;
use App\Http\Controllers\ArchivoSubtareaController;
use App\Http\Controllers\ControlCambioController;
use App\Http\Controllers\TipoElementoController;
use App\Http\Controllers\ClienteFinalController;
use App\Http\Controllers\TipoTrabajoController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\MotivoPausaController;
use App\Http\Controllers\MotivoSuspendidoController;
use App\Http\Controllers\MovilizacionSubtareaController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\RutaTareaController;
use App\Http\Controllers\SeguimientoController;
use App\Http\Controllers\SubtareaController;
use App\Http\Controllers\TendidoController;
use App\Http\Controllers\TareaController;
use Illuminate\Support\Facades\Route;

// Generar GET - POST - PUT - DELETE
Route::apiResources(
    [
        'tareas' => TareaController::class,
        'subtareas' => SubtareaController::class,
        'tipos-trabajos' => TipoTrabajoController::class,
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
        'seguimientos' => SeguimientoController::class,
    ],
    [
        'parameters' => [
            'tipos-trabajos' => 'tipo_trabajo',
            'tipos-elementos' => 'tipo_elemento',
            'clientes-finales' => 'cliente_final',
            'archivos-subtareas' => 'archivo_subtarea',
            'registros-tendidos' => 'registro_tendido',
            'motivos-pausas' => 'motivo_pausa',
            'motivos-suspendidos' => 'motivo_suspendido',
            'rutas-tareas' => 'ruta_tarea'
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

Route::get('export-seguimiento/{seguimiento}', [SeguimientoController::class, 'exportarSeguimiento']);

// Route::put('tareas/actualizar-fechas-reagendar/{tarea}', [TareaController::class, 'actualizarFechasReagendar']);

// Route::post('tareas/cancelar/{tarea}', [TareaController::class, 'cancelar']);

// Obtener los trabajos designados: de un grupo o empleado individual
Route::get('trabajo-asignado', [TrabajoAsignadoController::class, 'index']);

// Designación de rol lider de grupo durante la creacion de la subtarea
Route::put('designar-lider-grupo/{empleado}', [EmpleadoController::class, 'designarLiderGrupo']);

// Designación de rol secretario de grupo durante la creacion de la subtarea
//Route::post('designar-secretario-grupo', [EmpleadoController::class, 'designarSecretarioGrupo']);

// Bobina se reemplaza por metros
// Obtener las bobinas asignadas a un empleado para usarla durante la ejecución de un trabajo
// Route::get('bobinas-empleado-tarea', [TransaccionBodegaEgresoController::class, 'obtenerBobinas']);

// Obtener los materiales para tareas asignados a un empleado
Route::get('todos-materiales-empleado-tarea', [TransaccionBodegaEgresoController::class, 'obtenerTodosMateriales']);

// GET - POST - PUT del inicio de un tendido de FO (No son los registros)
Route::apiResource('tendidos', TendidoController::class)->except('show');

// Obtiene un registro de tendido filtrado por subtarea // REVISAR SI QUEDA, se toma como reemplazo en el show de arriba
Route::get('tendidos/{subtarea}', [TendidoController::class, 'show']);

// Reportes de material
Route::get('reportes-control-materiales', [ReporteControlMaterialController::class, 'index']);

Route::get('movilizacion-subtarea-destino-actual', [MovilizacionSubtareaController::class, 'destinoActual']);

/*************
 * Materiales
 *************/
// Obtener los materiales designados a un empleado para usarlos durante la ejecución de un trabajo
Route::get('materiales-empleado-tarea', [TransaccionBodegaEgresoController::class, 'obtenerMaterialesEmpleadoTareas']);

// Obtener los materiales del stock personal
Route::get('materiales-empleado', [TransaccionBodegaEgresoController::class, 'obtenerMaterialesEmpleado']);
