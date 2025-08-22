<?php

use App\Http\Controllers\ActivosFijos\ActivoFijoController;
use App\Http\Controllers\ActivosFijos\CategoriaMotivoConsumoActivoFijoController;
use App\Http\Controllers\ActivosFijos\MotivoConsumoActivoFijoController;
use App\Http\Controllers\ActivosFijos\SeguimientoConsumoActivosFijosController;
use App\Http\Controllers\TransaccionBodegaController;
use Illuminate\Support\Facades\Route;


Route::apiResources(
    [
        'activos-fijos' => ActivoFijoController::class,
        'seguimiento-consumo-activos-fijos' => SeguimientoConsumoActivosFijosController::class,
    ],
    [
        'parameters' => [
            'activos-fijos' => 'activo_fijo',
            'seguimiento-consumo-activos-fijos' => 'seguimiento_consumo_activo_fijo',
        ],
    ]
);

Route::get('entregas', [ActivoFijoController::class, 'entregas']); // Devuelve modelo TransaccionBodega
Route::get('categorias-motivos-consumo-activos-fijos', [CategoriaMotivoConsumoActivoFijoController::class, 'index']);
Route::get('motivos-consumo-activos-fijos', [MotivoConsumoActivoFijoController::class, 'index']);
Route::get('stock-responsables-activos-fijos', [ActivoFijoController::class, 'obtenerAsignacionesProductos']); // activos fijos asignados al usuario
Route::get('activos-fijos-asignados', [ActivoFijoController::class, 'obtenerActivosFijosAsignados']); // activos fijos asignados al usuario pasado
Route::get('reporte-activos-fijos', [ActivoFijoController::class, 'reporteActivosFijos']); // activos fijos asignados al usuario pasado
Route::get('activos-fijos-imprimir-etiqueta/{id}', [ActivoFijoController::class, 'printLabel']);
Route::post('activos-fijos-imprimir-etiqueta-personalizada', [ActivoFijoController::class, 'printCustomLabel']);
// Route::get('seguimiento-consumo-activos-fijos', [SeguimientoConsumoActivosFijosController::class, 'index']);

/*************************
 * Archivos polimorficos
 *************************/
Route::get('seguimiento-consumo-activos-fijos/files/{seguimiento_consumo_activo_fijo}', [SeguimientoConsumoActivosFijosController::class, 'indexFiles']);
Route::post('seguimiento-consumo-activos-fijos/files/{seguimiento_consumo_activo_fijo}', [SeguimientoConsumoActivosFijosController::class, 'storeFiles']);

