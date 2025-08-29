<?php

use App\Http\Controllers\RecursosHumanos\SeleccionContratacion\BancoPostulanteController;
use App\Http\Controllers\RecursosHumanos\SeleccionContratacion\ConocimientoController;
use App\Http\Controllers\RecursosHumanos\SeleccionContratacion\EntrevistaController;
use App\Http\Controllers\RecursosHumanos\SeleccionContratacion\ExamenController;
use App\Http\Controllers\RecursosHumanos\SeleccionContratacion\ModalidadController;
use App\Http\Controllers\RecursosHumanos\SeleccionContratacion\PostulacionController;
use App\Http\Controllers\RecursosHumanos\SeleccionContratacion\PostulanteController;
use App\Http\Controllers\RecursosHumanos\SeleccionContratacion\SolicitudPersonalController;
use App\Http\Controllers\RecursosHumanos\SeleccionContratacion\TipoPuestoController;
use App\Http\Controllers\RecursosHumanos\SeleccionContratacion\UserExternalController;
use App\Http\Controllers\RecursosHumanos\SeleccionContratacion\VacanteController;
use App\Http\Controllers\RecursosHumanos\TipoDiscapacidadController;
use Illuminate\Support\Facades\Route;

Route::apiResources(
    [
        /*************************************************
         *  Módulo de Selección y contratación de personal
         *************************************************/
        'postulantes' => PostulanteController::class,
        'solicitudes-nuevo-personal' => SolicitudPersonalController::class,
        'tipos-puestos' => TipoPuestoController::class,
        'tipos-discapacidades' => TipoDiscapacidadController::class,
        'conocimientos' => ConocimientoController::class,
        'modalidades' => ModalidadController::class,
        'postulaciones-vacantes' => PostulacionController::class,
        'bancos-postulantes' => BancoPostulanteController::class,
        'entrevistas' => EntrevistaController::class,
        'examenes-postulantes' => ExamenController::class,
        'usuarios-externos'=> UserExternalController::class,
    ],
    [
        'parameters' => [
            'solicitudes-nuevo-personal' => 'solicitud',
            'tipos-puestos' => 'tipo',
            'modalidades' => 'modalidad',
            'postulaciones-vacantes' => 'postulacion',
            'bancos-postulantes' => 'banco',
            'examenes-postulantes' => 'examen',
            'usuarios-externos' => 'user',
        ]
    ]
);
Route::post('vacantes', [VacanteController::class, 'store']);
Route::put('vacantes/{vacante}', [VacanteController::class, 'update']);
Route::get('vacantes-favoritas', [VacanteController::class, 'indexFavoritas']);
Route::post('vacante-favorita/{vacante}', [VacanteController::class, 'favorite']);
Route::post('postulaciones-vacantes/calificar/{postulacion}', [PostulacionController::class, 'calificar']);
Route::post('postulaciones-vacantes/descartar/{postulacion}', [PostulacionController::class, 'descartar']);
Route::post('postulaciones-vacantes/seleccionar/{postulacion}', [PostulacionController::class, 'seleccionar']);
Route::post('postulaciones-vacantes/dar-alta/{postulacion}', [PostulacionController::class, 'darAlta']);
Route::post('validar-token-test-personalidad/{token}', [PostulacionController::class, 'validarTokenTestPersonalidad']);
Route::post('habilitar-test-personalidad/{postulacion}', [PostulacionController::class, 'habilitarTestPersonalidad']);
Route::get('descargar-evaluacion-personalidad/{postulacion}', [PostulacionController::class, 'descargarTestPersonalidadCompletado']);
Route::get('tiene-evaluacion-personalidad/{postulacion}', [PostulacionController::class, 'chequearTieneEvaluacionPersonalidad']);


//listar archivos
Route::get('solicitudes-nuevo-personal/files/{solicitud}', [SolicitudPersonalController::class, 'indexFiles']);
Route::get('curriculums-usuario', [PostulacionController::class, 'curriculumUsuario']);
Route::get('referencias-usuario', [PostulacionController::class, 'referenciasUsuario']);
Route::get('postulaciones-vacantes/files/{postulacion}', [PostulacionController::class, 'indexFiles']);

//guardar archivos
Route::post('solicitudes-nuevo-personal/files/{solicitud}', [SolicitudPersonalController::class, 'storeFiles']);
Route::post('postulaciones-vacantes/files/{postulacion}', [PostulacionController::class, 'storeFiles']);
