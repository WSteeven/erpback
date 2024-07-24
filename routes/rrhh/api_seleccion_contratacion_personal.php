<?php

use App\Http\Controllers\RecursosHumanos\SeleccionContratacion\ConocimientoController;
use App\Http\Controllers\RecursosHumanos\SeleccionContratacion\PostulanteController;
use App\Http\Controllers\RecursosHumanos\SeleccionContratacion\SolicitudPersonalController;
use App\Http\Controllers\RecursosHumanos\SeleccionContratacion\TipoPuestoController;
use App\Http\Controllers\RecursosHumanos\SeleccionContratacion\VacanteController;
use App\Http\Controllers\RecursosHumanos\TipoDiscapacidadController;
use Illuminate\Support\Facades\Route;

Route::apiResources(
    [
        /*************************************************
         *  Módulo de Selección y contratación de personal
         *************************************************/
        'postulantes' => PostulanteController::class,
//        'vacantes' => VacanteController::class,
        'solicitudes-nuevo-personal' => SolicitudPersonalController::class,
        'tipos-puestos' => TipoPuestoController::class,
        'tipos-discapacidades' => TipoDiscapacidadController::class,
        'conocimientos'=>ConocimientoController::class
    ],
    [
        'parameters' => [
            'solicitudes-nuevo-personal' => 'solicitud',
            'tipos-puestos' => 'tipo'
        ]
    ]
);
Route::post('vacantes', [VacanteController::class, 'store']);
Route::put('vacantes/{vacante}', [VacanteController::class, 'update']);


//listar archivos
Route::get('solicitudes-nuevo-personal/files/{solicitud}', [SolicitudPersonalController::class, 'indexFiles']);
//guardar archivos
Route::post('solicitudes-nuevo-personal/files/{solicitud}', [SolicitudPersonalController::class, 'storeFiles']);
