<?php

//  Generar GET - POST - PUT - DELETE


use App\Http\Controllers\RecursosHumanos\ControlPersonal\HorarioLaboralController;
use App\Http\Controllers\RecursosHumanos\ControlPersonal\AsistenciaController;
use App\Http\Controllers\RecursosHumanos\ControlPersonal\AtrasosController;

use Illuminate\Support\Facades\Route;

/**
 * Consultar Asistencia de Biometrico
 */

Route::get('/asistencias/sincronizar', [AsistenciaController::class, 'store']);
Route::get('/atrasos/sincronizar', [AtrasosController::class, 'store']);


Route::apiResources(
    [
        'asistencias' => AsistenciaController::class,
        'atrasos' => AtrasosController::class
    ],
    [
        'parameters' => [
            'asistencias' => 'asistencia',
            'atrasos' => 'atraso',
        ]

    ]
);

/**Otras Rutas */

/**Atrasos */
Route::get('/atrasos', [AtrasosController::class, 'index']);


/**Horario Laboral */
Route::get('/horario-laboral', [HorarioLaboralController::class, 'index']);
Route::post('/horario-laboral', [HorarioLaboralController::class, 'store']);
