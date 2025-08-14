<?php

//  Generar GET - POST - PUT - DELETE


use App\Http\Controllers\ControlPersonal\AtrasoController;
use App\Http\Controllers\ControlPersonal\MarcacionController;
use App\Http\Controllers\ControlPersonal\OficinaBiometricoController;
use App\Http\Controllers\RecursosHumanos\ControlPersonal\HorarioLaboralController;
use Illuminate\Support\Facades\Route;


Route::apiResources(
    [
        'atrasos' => AtrasoController::class,
        'horarios-laborales' => HorarioLaboralController::class,
        'marcaciones' => MarcacionController::class,
        'oficinas' => OficinaBiometricoController::class,
    ],
    [
        'parameters' => [
            'asistencias' => 'asistencia',
            'atrasos' => 'atraso',
            'horarios-laborales' => 'horario',
            'marcaciones' => 'marcacion',
        ]

    ]
);


/**
 * Consultar Asistencia de Biometrico
 */
Route::post('dashboard', [MarcacionController::class, 'dashboard']);
Route::get('sincronizar-marcaciones', [MarcacionController::class, 'sincronizarAsistencias']);
Route::get('sincronizar-atrasos', [AtrasoController::class, 'sincronizarAtrasos']);
/**Otras Rutas */


