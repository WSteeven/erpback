<?php

use App\Http\Controllers\RecursosHumanos\TrabajoSocial\FichaSocioeconomicaController;
use App\Http\Controllers\RecursosHumanos\TrabajoSocial\VisitaDomiciliariaController;

Route::apiResources([
    'fichas-socioeconomicas' => FichaSocioeconomicaController::class,
    'visitas-domiciliarias' => VisitaDomiciliariaController::class,
], [
    'parameters' => [
        'fichas-socioeconomicas' => 'ficha',
        'visitas-domiciliarias' => 'visita',

    ]
]);

Route::get('empleado-tiene-ficha-socioeconomica/{empleado}', [FichaSocioeconomicaController::class, 'empleadoTieneFichaSocioeconomica']);
Route::get('ultima-ficha-socioeconomica/{empleado}', [FichaSocioeconomicaController::class, 'ultimaFichaEmpleado']);

Route::get('empleado-tiene-visita-domiciliaria/{empleado}', [VisitaDomiciliariaController::class, 'empleadoTieneVisitaDomiciliaria']);
Route::get('ultima-visita-domiciliaria/{empleado}', [VisitaDomiciliariaController::class, 'ultimaVisitaDomiciliariaEmpleado']);
