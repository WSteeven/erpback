<?php

use App\Http\Controllers\Medico\CuestionarioPublicoController;
use App\Http\Controllers\Medico\PreguntaController;
use App\Http\Controllers\Medico\TipoCuestionarioController;
use App\Http\Controllers\RecursosHumanos\EstadoCivilController;
use App\Http\Resources\CantonResource;
use App\Models\Canton;
use App\Models\Pais;
use App\Models\Provincia;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Src\Shared\ValidarIdentificacion;

/*
|--------------------------------------------------------------------------
| API publica - Routes
|--------------------------------------------------------------------------
| Rutas que son accesibles sin necesidad de autenticarse
|
*/

/***************
 * Miscelaneas
 ***************/
Route::post('/validar-cedula', function (Request $request) {
    $validado = (new ValidarIdentificacion())->validarCedula($request['cedula']);
    return response()->json($validado);
});

/****************
 * Localizacion
 ****************/
Route::get('paises', fn () => ['results' => Pais::filter()->get()]);
Route::get('provincias', fn () => ['results' => Provincia::filter()->get()]);
Route::get('cantones', function () {
    $results = Canton::ignoreRequest(['campos'])->filter()->get();
    $results = CantonResource::collection($results);
    return response()->json(compact('results'));
});

/***************************
 * Rutas del modulo medico
 ***************************/
Route::prefix('medico')->group(function () {
    // Rutas normales
    Route::get('tipos-cuestionarios', [TipoCuestionarioController::class, 'index']);
    Route::get('preguntas', [PreguntaController::class, 'index']);

    // ApiResources
    Route::apiResources(
        [
            'cuestionarios-publicos' => CuestionarioPublicoController::class,
        ],
        [
            'parameters' => [
                'cuestionarios-publicos' => 'cuestionario_publico'
            ]
        ]
    );
});

/***************************
 * Rutas del modulo de rrhh
 ***************************/
Route::prefix('recursos-humanos')->group(function () {
    Route::get('estado_civil', [EstadoCivilController::class, 'index']);
});
