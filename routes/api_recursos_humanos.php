<?php

use App\Http\Controllers\EmpleadoController;
use Illuminate\Support\Facades\Route;

Route::apiResources(
    [
        'rol-pagos' => RolPagosController::class,
    ],
    [
        'parameters' => [],
    ]
);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('salario/{id}', [EmpleadoController::class,'salario']);
});
