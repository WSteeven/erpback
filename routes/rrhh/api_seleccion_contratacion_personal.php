<?php

use App\Http\Controllers\RecursosHumanos\SeleccionContratacion\ConocimientoController;
use App\Http\Controllers\RecursosHumanos\SeleccionContratacion\PostulanteController;
use App\Http\Controllers\RecursosHumanos\SeleccionContratacion\SolicitudPuestoEmpleoController;
use App\Http\Controllers\RecursosHumanos\SeleccionContratacion\TipoPuestoTrabajoController;
use App\Http\Controllers\RecursosHumanos\SeleccionContratacion\VacanteController;
use App\Http\Controllers\RecursosHumanos\TipoDiscapacidadController;
use Illuminate\Support\Facades\Route;

Route::apiResources(
    [
        /*************************************************
         *  Modulo de Seleción y contratación de personal
         *************************************************/
        'postulantes' => PostulanteController::class,
        'vacantes' => VacanteController::class,
        'solicitudes-nuevo-personal' => SolicitudPuestoEmpleoController::class,
        'tipos-puestos-trabajos' => TipoPuestoTrabajoController::class,
        'tipos-discapacidades' => TipoDiscapacidadController::class,
        'conocimientos'=>ConocimientoController::class
    ],
    [
        'parameters' => [
            'solicitudes-nuevo-personal' => 'solicitud',
            'tipos-puestos-trabajos' => 'tipo'
        ]
    ]
);
