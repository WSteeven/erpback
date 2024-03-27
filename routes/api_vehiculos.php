<?php

use App\Http\Controllers\Vehiculos\BitacoraVehicularController;
use App\Http\Controllers\Vehiculos\CombustibleController;
use App\Http\Controllers\Vehiculos\ConductorController;
use App\Http\Controllers\Vehiculos\MatriculaController;
use App\Http\Controllers\Vehiculos\MultaConductorController;
use App\Http\Controllers\Vehiculos\PlanMantenimientoController;
use App\Http\Controllers\Vehiculos\SeguroVehicularController;
use App\Http\Controllers\Vehiculos\ServicioController;
use App\Http\Controllers\Vehiculos\TipoVehiculoController;
use App\Http\Controllers\Vehiculos\VehiculoController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::apiResources(
    [
        'combustibles' => CombustibleController::class,
        'conductores' => ConductorController::class,
        'matriculas' => MatriculaController::class,
        'multas' => MultaConductorController::class,
        'vehiculos' => VehiculoController::class,
        'bitacoras-vehiculos' => BitacoraVehicularController::class,
        'servicios' => ServicioController::class,
        'seguros' => SeguroVehicularController::class,
        'planes-mantenimientos' => PlanMantenimientoController::class,
        'tipos-vehiculos' => TipoVehiculoController::class,
        'asignaciones-vehiculos' => asignacionvehiculoController::class,
    ],
    [
        'parameters' => [
            'bitacoras-vehiculos' => 'bitacora',
            'conductores' => 'conductor',
            'planes-mantenimientos' => 'vehiculo',
            'tipos-vehiculos' => 'tipo',
        ],
        'middleware' => ['auth:sanctum']
    ]
);

// pagar multas
Route::post('multas/marcar-pagada/{multa}', [MultaConductorController::class, 'pagar'])->middleware('auth:sanctum');
// pagar matricula vehicular
Route::post('matriculas/marcar-pagada/{matricula}', [MatriculaController::class, 'pagar'])->middleware('auth:sanctum');

// listar archivos
Route::get('vehiculos/files/{vehiculo}', [VehiculoController::class, 'indexFiles'])->middleware('auth:sanctum');

// guardar archivos
Route::post('vehiculos/files/{vehiculo}', [VehiculoController::class, 'storeFiles'])->middleware('auth:sanctum');


//anular
Route::post('servicios/anular/{servicio}', [ServicioController::class, 'desactivar'])->middleware('auth:sanctum');
