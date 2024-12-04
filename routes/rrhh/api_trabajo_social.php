<?php

use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\RecursosHumanos\TrabajoSocial\FichaSocioeconomicaController;

Route::apiResources([
    'fichas-socioeconomicas' => FichaSocioeconomicaController::class,
], [
    'parameters' => [
        'fichas-socioeconomicas' => 'ficha',

    ]
]);

Route::get('empleado-tiene-ficha-socioeconomica/{empleado}', [FichaSocioeconomicaController::class, 'empleadoTieneFichaSocioeconomica']);
Route::get('ultima-ficha-socioeconomica/{empleado}', [FichaSocioeconomicaController::class, 'ultimaFichaEmpleado']);
