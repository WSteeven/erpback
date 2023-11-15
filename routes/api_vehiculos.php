<?php

use App\Http\Controllers\Vehiculos\BitacoraVehicularController;
use App\Http\Controllers\Vehiculos\CombustibleController;
use App\Http\Controllers\Vehiculos\ConductorController;
use App\Http\Controllers\Vehiculos\MultaConductorController;
use App\Http\Controllers\Vehiculos\VehiculoController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::apiResources(
    [
        'combustibles' => CombustibleController::class,
        'conductores' => ConductorController::class,
        'multas' => MultaConductorController::class,
        'vehiculos' => VehiculoController::class,
        'bitacoras-vehiculos' => BitacoraVehicularController::class,
    ],
    [
        'parameters' => [
            'bitacoras-vehiculos' => 'bitacora',
            'conductores' => 'conductor',
        ],
        'middleware' => ['auth:sanctum']
    ]
);

// pagar multas
Route::post('multas/marcar-pagada/{multa}', [MultaConductorController::class, 'pagar'])->middleware('auth:sanctum');

// listar archivos
Route::get('vehiculos/files/{vehiculo}', [VehiculoController::class, 'indexFiles'])->middleware('auth:sanctum');

// guardar archivos
Route::post('vehiculos/files/{vehiculo}', [VehiculoController::class, 'storeFiles'])->middleware('auth:sanctum');
