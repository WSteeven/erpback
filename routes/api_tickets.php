<?php

use App\Http\Controllers\ArchivoSeguimientoTicketController;
use App\Http\Controllers\ArchivoTicketController;
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
        'archivos-tickets' => ArchivoTicketController::class,
        'archivos-seguimientos-tickets' => ArchivoSeguimientoTicketController::class,
        'motivos-pausas-tickets' => MotivoPausaTicketController::class,
        'motivos-cancelados-tickets' => MotivoCanceladoTicketController::class,
    ],
    [
        'parameters' => [
            'tipos-tickets' => 'tipo_ticket',
            'archivos-tickets' => 'archivo_ticket',
            'archivos-seguimientos-tickets' => 'archivo_seguimiento_ticket',
            'motivos-pausas-tickets' => 'motivo_pausa_ticket',
            'motivos-cancelados-tickets' => 'motivo_cancelado_ticket',
        ],
    ]
);

// Modificar los estados de los tickets
Route::prefix('tickets')->group(function () {
    Route::post('cancelar/{ticket}', [TicketController::class, 'cancelar']);
    Route::post('cambiar-responsable/{ticket}', [TicketController::class, 'cambiarResponsable']);
});
