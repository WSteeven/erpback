<?php


use App\Http\Controllers\Appenate\ProgresivaController;

Route::apiResource('progresivas', ProgresivaController::class, ['only' => ['index', 'show', 'update', 'destroy']]);


Route::post('progresivas', [ProgresivaController::class, 'store'])->middleware('verificar.apikey');

