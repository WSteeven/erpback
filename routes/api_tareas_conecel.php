<?php

use App\Http\Controllers\Conecel\GestionTareas\TipoActividadController;

Route::apiResources([
    'tareas' => TipoActividadController::class,
    'tipos-actividades' => TipoActividadController::class,
], [
    'parameters' => [
        'tipos-actividades' => 'tipo',
    ],
]);
