<?php

use App\Http\Controllers\ActivosFijos\ActivoFijoController;
use App\Http\Controllers\ActivosFijos\CategoriaMotivoConsumoActivoFijoController;
use App\Http\Controllers\ActivosFijos\MotivoConsumoActivoFijoController;
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

Route::get('entregas', [ActivoFijoController::class, 'entregas']);
Route::get('categorias-motivos-consumo-activos-fijos', [CategoriaMotivoConsumoActivoFijoController::class, 'index']);
Route::get('motivos-consumo-activos-fijos', [MotivoConsumoActivoFijoController::class, 'index']);
