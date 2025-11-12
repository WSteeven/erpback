<?php

use App\Http\Controllers\Conecel\GestionTareas\TareaController;
use App\Http\Controllers\Hunter\PosicionHunterController;

Route::apiResources([
    'tareas' => TareaController::class,
], [
    'parameters' => [
    ],
]);

Route::post('subir-tareas-lotes/{grupo_id}', [TareaController::class, 'subirTareasLotes']);
Route::get('ubicaciones-gps', [PosicionHunterController::class, 'ubicacionesGPS']);
Route::get('ubicaciones-gps-tareas', [PosicionHunterController::class, 'ubicacionesGPSconTareas']);
