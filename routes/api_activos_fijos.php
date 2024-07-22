<?php

use App\Http\Controllers\ActivosFijos\ActivoFijoController;
use App\Http\Controllers\TransaccionBodegaEgresoController;
use App\Http\Controllers\TransaccionBodegaIngresoController;
use Illuminate\Support\Facades\Route;


Route::apiResources(
    [
        'activos-fijos' => ActivoFijoController::class,
    ],
    [
        'parameters' => [
            'activos-fijos' => 'activo_fijo',
        ],
    ]
);

Route::get('egresos', [TransaccionBodegaEgresoController::class, 'obtenerEgresos']);
Route::get('ingresos', [TransaccionBodegaIngresoController::class, 'obtenerIngresos']);
