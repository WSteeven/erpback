<?php

use App\Http\Controllers\RecursosHumanos\Capacitacion\EvaluacionDesempenoController;
use App\Http\Controllers\RecursosHumanos\Capacitacion\FormularioController;
use Illuminate\Support\Facades\Route;

Route::apiResources([
//    'formularios' => FormularioController::class,
    'evaluaciones-desempeno' => EvaluacionDesempenoController::class,
], [
    'parameters' => [
        'evaluaciones-desempeno' => 'evaluacion'
    ]
]);

Route::get('evaluaciones-desempeno/imprimir/{evaluacion}', [EvaluacionDesempenoController::class, 'imprimir']);

Route::apiResource('formularios', FormularioController::class)->except(['show']);

