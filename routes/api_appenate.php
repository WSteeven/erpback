<?php


use App\Http\Controllers\Appenate\ProgresivaController;
use App\Http\Controllers\Conecel\GestionTareas\TareaController;

Route::apiResource('progresivas', ProgresivaController::class, ['only' => ['index', 'show', 'update', 'destroy']]);


Route::post('progresivas', [ProgresivaController::class, 'store'])->middleware('verificar.apikey');

Route::get('imprimir-ot-progresiva/{progresiva}', [ProgresivaController::class, 'imprimirOrdenTrabajo']);
Route::get('imprimir-kml-progresiva/{progresiva}', [ProgresivaController::class, 'imprimirKml']);
Route::get('imprimir-excel-progresiva/{progresiva}', [ProgresivaController::class, 'imprimirMaterialesUtilizados']);

