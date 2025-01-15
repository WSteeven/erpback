<?php

//  Generar GET - POST - PUT - DELETE


use App\Http\Controllers\RecursosHumanos\ControlPersonal\HorarioLaboralController;
use App\Http\Controllers\RecursosHumanos\ControlPersonal\AsistenciaController;
use App\Http\Controllers\RecursosHumanos\ControlPersonal\AtrasosController;

use Illuminate\Support\Facades\Route;


Route::apiResources(
    [
        'asistencias' => AsistenciaController::class,
        'atrasos' => AtrasosController::class,
        'horarios-laborales' => HorarioLaboralController::class
    ],
    [
        'parameters' => [
            'asistencias' => 'asistencia',
            'atrasos' => 'atraso',
            'horarios-laborales' => 'horario'
        ]

    ]
);


/**
 * Consultar Asistencia de Biometrico
 */

Route::get('sincronizar-asistencias', [AsistenciaController::class, 'sincronizarAsistencias']);
Route::get('atrasos/sincronizar', [AtrasosController::class, 'store']);
/**Otras Rutas */

