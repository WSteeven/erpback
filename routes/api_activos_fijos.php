<?php

use Illuminate\Support\Facades\Route;

// Generar GET - POST - PUT - DELETE
Route::apiResources(
    [
        // 'etapas' => EtapaController::class,
    ],
    [
        'parameters' => [
            // 'subcentros-costos' => 'subcentro',
        ],
    ]
);