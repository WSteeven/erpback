<?php

use App\Http\Controllers\ActividadRealizadaSeguimientoTicketController;
use App\Http\Controllers\ArchivoSeguimientoTicketController;
use App\Http\Controllers\ArchivoTicketController;
use App\Http\Controllers\CategoriaTipoTicketController;
use App\Http\Controllers\DashboardTicketController;
use App\Http\Controllers\MotivoCanceladoTicketController;
use App\Http\Controllers\MotivoPausaTicketController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TipoTicketController;
use Illuminate\Support\Facades\Route;

// Generar GET - POST - PUT
Route::apiResources(
    [
        'tickets' => TicketController::class,
        'tipos-tickets' => TipoTicketController::class,
        'categorias-tipos-tickets' => CategoriaTipoTicketController::class,
        'archivos-tickets' => ArchivoTicketController::class,
        'archivos-seguimientos-tickets' => ArchivoSeguimientoTicketController::class,
        'motivos-pausas-tickets' => MotivoPausaTicketController::class,
        'motivos-cancelados-tickets' => MotivoCanceladoTicketController::class,
        'actividades-realizadas-seguimientos-tickets' => ActividadRealizadaSeguimientoTicketController::class,
    ],
    [
        'parameters' => [
            'tipos-tickets' => 'tipo_ticket',
            'archivos-tickets' => 'archivo_ticket',
            'archivos-seguimientos-tickets' => 'archivo_seguimiento_ticket',
            'motivos-pausas-tickets' => 'motivo_pausa_ticket',
            'motivos-cancelados-tickets' => 'motivo_cancelado_ticket',
            'actividades-realizadas-seguimientos-tickets' => 'actividad_realizada',
        ],
    ]
);

// Modificar los estados de los tickets
Route::prefix('tickets')->group(function () {
    Route::post('cancelar/{ticket}', [TicketController::class, 'cancelar']);
    Route::post('cambiar-responsable/{ticket}', [TicketController::class, 'cambiarResponsable']);
    Route::post('ejecutar/{ticket}', [TicketController::class, 'ejecutar']);
    Route::post('pausar/{ticket}', [TicketController::class, 'pausar']);
    Route::post('reanudar/{ticket}', [TicketController::class, 'reanudar']);
    Route::post('finalizar/{ticket}', [TicketController::class, 'finalizar']);
    Route::post('finalizar-no-solucion/{ticket}', [TicketController::class, 'finalizarNoSolucion']);
    Route::post('rechazar/{ticket}', [TicketController::class, 'rechazar']);
    Route::post('calificar/{ticket}', [TicketController::class, 'calificar']);
    Route::get('obtener-pausas/{ticket}', [TicketController::class, 'obtenerPausas']);
    Route::get('obtener-rechazados/{ticket}', [TicketController::class, 'obtenerRechazados']);
});

/***********
 * Dashboard
 ***********/
Route::get('dashboard', [DashboardTicketController::class, 'index']);
