<?php

use App\Http\Controllers\CombustibleController;
use App\Http\Controllers\VehiculoController;
use Illuminate\Support\Facades\Route;

Route::apiResources(
    [
        'combustibles' => CombustibleController::class,
        'vehiculos' => VehiculoController::class,
    ]/* ,
    [
        'parameters'=>[

        ];
    ] */
);
