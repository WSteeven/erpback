<?php

use App\Http\Controllers\Conecel\GestionTareas\TareaController;
use App\Http\Controllers\Conecel\GestionTareas\TipoActividadController;
use App\Http\Controllers\Hunter\PosicionHunterController;

Route::apiResources([
    'tareas' => TareaController::class,
    'tipos-actividades' => TipoActividadController::class,
], [
    'parameters' => [
        'tipos-actividades' => 'tipo',
    ],
]);

Route::post('subir-tareas-lotes/{grupo_id}', [TareaController::class, 'subirTareasLotes']);
Route::get('ubicaciones-gps', [PosicionHunterController::class, 'ubicacionesGPS']);
