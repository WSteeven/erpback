<?php

use App\Http\Controllers\Seguridad\ReporteAlimentacionController;
use App\Http\Controllers\Seguridad\ActividadBitacoraController;
use App\Http\Controllers\Seguridad\BitacoraController;
use App\Http\Controllers\Seguridad\MiembroZonaController;
use App\Http\Controllers\Seguridad\PrendaZonaController;

use App\Http\Controllers\Seguridad\RestriccionPrendaZonaController;
use App\Http\Controllers\Seguridad\TipoEventoBitacoraController;
use App\Http\Controllers\Seguridad\ZonaController;

use Illuminate\Support\Facades\Route;

// Generar GET - POST - PUT - DELETE
Route::apiResources(
    [
        'zonas' => ZonaController::class,
        'miembros-zonas' => MiembroZonaController::class,
        'prendas-zonas' => PrendaZonaController::class,
        'tipos-eventos-bitacoras' => TipoEventoBitacoraController::class,
        'bitacoras' => BitacoraController::class,
        'restricciones-prendas-zonas' => RestriccionPrendaZonaController::class,
        'actividades-bitacoras' => ActividadBitacoraController::class,
    ],
    [
        'parameters' => [
            'zonas' => 'zona',
            'miembros-zonas' => 'miembro_zona',
            'prendas-zonas' => 'prenda_zona',
            'tipos-eventos-bitacoras' => 'tipo_evento_bitacora',
            'bitacoras' => 'bitacora',
            'restricciones-prendas-zonas' => 'restriccion_prenda_zona',
            'actividades-bitacoras' => 'actividad_bitacora',
        ],
    ]
);

Route::delete('/restricciones-prendas-zonas', [RestriccionPrendaZonaController::class, 'destroyMultipleByIds']);
Route::delete('/restricciones-prendas-zonas-datos', [RestriccionPrendaZonaController::class, 'destroyMultipleByData']);

Route::get('/prendas-zonas-existe', [PrendaZonaController::class, 'existeZona']);
Route::get('/prendas-zonas-permitidas', [PrendaZonaController::class, 'consultarPrendasZona']);

/*************************
 * Archivos polimorficos
 *************************/
Route::get('actividades-bitacoras/files/{actividad_bitacora}', [ActividadBitacoraController::class, 'indexFiles']);
Route::post('actividades-bitacoras/files/{actividad_bitacora}', [ActividadBitacoraController::class, 'storeFiles']);

/***************
 * Imprimir reportes
 ***************/
Route::post('bitacoras/reportes', [ReporteAlimentacionController::class, 'index']);


// Route::get('seguimientos-accidentes/imprimir/{seguimiento_accidente}', [SeguimientoAccidenteController::class, 'informeAccidente']);
