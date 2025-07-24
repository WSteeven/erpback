<?php

use App\Http\Controllers\Medico\CuestionarioPublicoController;
use App\Http\Controllers\Medico\IdentidadGeneroController;
use App\Http\Controllers\Medico\LinkCuestionarioPublicoController;
use App\Http\Controllers\Medico\PreguntaController;
use App\Http\Controllers\Medico\TipoCuestionarioController;
use App\Http\Controllers\RecursosHumanos\Capacitacion\FormularioController;
use App\Http\Controllers\RecursosHumanos\EstadoCivilController;
use App\Http\Controllers\RecursosHumanos\SeleccionContratacion\EvaluacionPersonalidadController;
use App\Http\Controllers\RecursosHumanos\SeleccionContratacion\VacanteController;
use App\Http\Resources\CantonResource;
use App\Models\Canton;
use App\Models\Pais;
use App\Models\Provincia;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Src\App\Medico\CuestionariosRespondidosService;
use Src\Shared\ValidarIdentificacion;
use Illuminate\Validation\ValidationException;

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

Route::get('/send-firebase', function () {
    function sendFirebaseNotification($fcmToken, $title, $body)
    {
        // $SERVER_API_KEY = 'BIAM3GntAC5X5An6PyBuIW4rbDzQtPcT9NE7le9mxyOiSFNgYvqI55z45Mex2F43llwM3Tas-2G8OCwPY52GYYg';
        $SERVER_API_KEY = 'RCZkEKOKEpedrxtWybAVTNWwfRZp_S3QIhaBpsid23s';

        $response = Http::withHeaders([
            'Authorization' => 'key=' . $SERVER_API_KEY,
            'Content-Type' => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', [
            'to' => $fcmToken,
            'notification' => [
                'title' => $title,
                'body' => $body,
                'sound' => 'default',
            ],
            'data' => [
                'extra_data' => 'valor', // opcional
            ]
        ]);

        return $response->json();
    }

    sendFirebaseNotification('fqCOykV-RNWPKHXLceiY0w:APA91bFhXqgtg54tk8oiVXE_oAM3Yyv-FmcmJDe-DrIiq4Zn53nNs42N5qs26JOtqBCzQnD300STcqquEXyntWkpNOhGg-XqTcbu2ymSowPVeo4JVKhV__Q', '¡Tienes una nueva tarea!', 'Haz clic para verla.');
});

/****************
 * Localizacion
 ****************/
Route::get('paises', fn() => ['results' => Pais::filter()->get()]);
Route::get('provincias', fn() => ['results' => Provincia::filter()->get()]);
Route::get('cantones', function () {
    $results = Canton::ignoreRequest(['campos'])->filter()->get();
    $results = CantonResource::collection($results);
    return response()->json(compact('results'));
});

/***************************
 * Rutas del módulo médico
 ***************************/
Route::prefix('medico')->group(function () {
    // Rutas normales
    Route::get('identidades-generos', [IdentidadGeneroController::class, 'index']);
    Route::get('tipos-cuestionarios', [TipoCuestionarioController::class, 'index']);
    Route::get('preguntas', [PreguntaController::class, 'index']);
    Route::get('links-cuestionarios-publicos', [LinkCuestionarioPublicoController::class, 'index']);
    Route::post('/verificar-cuestionario-publico-lleno', function (Request $request) {
        if ((new CuestionariosRespondidosService())->personaYaLlenoCuestionario($request['identificacion'], $request['tipo_cuestionario_id']))
            throw ValidationException::withMessages(['cuestionario_completado' => ['Usted ya completó el cuestionario para este año. </br> Su respuesta no se guardará.']]);
    });

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
 * Rutas del módulo de rrhh
 ***************************/
Route::prefix('recursos-humanos')->group(function () {
    Route::get('estado_civil', [EstadoCivilController::class, 'index']);
});

/***************************
 * Rutas del módulo Selección y Contratación de Personal
 ***************************/
Route::prefix('seleccion-contratacion')->group(function () {
    Route::get('vacantes', [VacanteController::class, 'index']);
    Route::get('vacantes/{vacante}', [VacanteController::class, 'show']);
    Route::get('vacantes/show-preview/{vacante}', [VacanteController::class, 'showPreview']);
    Route::apiResources(['evaluaciones-personalidades' => EvaluacionPersonalidadController::class], [
        'parameters' => [
            'evaluaciones-personalidades' => 'evaluacion',
        ]
    ]);
});

Route::prefix('capacitacion')->group(function (){
    Route::get('formularios/{formulario}', [FormularioController::class, 'show']);
});

Route::view('reporte-accidente', 'sso.pdf.informe_accidente');
