<?php

//  Generar GET - POST - PUT - DELETE

use App\Http\Controllers\RecursosHumanos\ControlPersonal\AsistenciaController;
use Illuminate\Support\Facades\Route;


Route::apiResources(
    [
        'asistencias' => AsistenciaController::class,
    ],
    [
        'parameters' => [
            'asistencias' => 'asistencia'
        ]

    ]
);
/**
 * Consultar Asistencia de Biometrico
 */
Route::get('/asistencias/sincronizar', [AsistenciaController::class, 'sincronizarAsistencias']);
