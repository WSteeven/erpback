<?php

use App\Http\Controllers\DepartamentoController;
use Illuminate\Support\Facades\Route;

// Generar GET - POST - PUT - DELETE
Route::apiResources(
    [
        'departamentos' => DepartamentoController::class,
    ],
);
