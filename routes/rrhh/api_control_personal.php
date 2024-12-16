<?php

//  Generar GET - POST - PUT - DELETE


use App\Http\Controllers\RecursosHumanos\ControlPersonal\HorarioLaboralController;
use App\Http\Controllers\RecursosHumanos\ControlPersonal\AsistenciaController;

use Illuminate\Support\Facades\Route;




/**
 * Consultar Asistencia de Biometrico
 */

Route::get('/asistencias/sincronizar', [AsistenciaController::class, 'store']);


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



/**Otras Rutas */

Route::get('/horario-laboral', [HorarioLaboralController::class, 'index']);
Route::post('/horario-laboral', [HorarioLaboralController::class, 'store']);

