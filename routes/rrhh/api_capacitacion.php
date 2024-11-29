<?php

use App\Http\Controllers\RecursosHumanos\Capacitacion\FormularioController;
use Illuminate\Support\Facades\Route;

Route::apiResources([
    'formularios' => FormularioController::class,
], [
    'parameters' => [

    ]
]);

