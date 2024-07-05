<?php

use App\Http\Controllers\RecursosHumanos\SeleccionContratacion\SolicitudPuestoEmpleoController;
use Illuminate\Support\Facades\Route;

Route::apiResources(
    [
        'solicitud-puesto-empleo' => SolicitudPuestoEmpleoController::class,
    ],
    [
        'parameters' => [
            'solicitud-puesto-empleo' => 'solicitud',
        ]
    ]
);
