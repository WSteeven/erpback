<?php

use App\Http\Controllers\Vehiculos\BitacoraVehicularController;
use App\Http\Controllers\Vehiculos\CombustibleController;
use App\Http\Controllers\Vehiculos\VehiculoController;
use App\Http\Resources\EmpleadoResource;
use App\Http\Resources\UserResource;
use App\Models\Empleado;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::apiResources(
    [
        'combustibles' => CombustibleController::class,
        'vehiculos' => VehiculoController::class,
        'bitacoras-vehiculos' => BitacoraVehicularController::class,
    ],
    [
        'parameters' => [
            'bitacoras-vehiculos' => 'bitacora',
        ],
        'middleware' => ['auth:sanctum']
    ]
);

Route::get('empleados-choferes', fn () => ['results' => UserResource::collection(User::role(User::CHOFER)->with('empleado')->get())])->middleware('auth:sanctum'); //usuarios con rol de chofer
