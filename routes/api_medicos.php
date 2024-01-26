<?php

use App\Http\Controllers\ExamenesController;
use App\Http\Controllers\Medico\CategoriaExamenController;
use App\Http\Controllers\Medico\ConfiguracionExamenCampoController;
use App\Http\Controllers\Medico\ConfiguracionExamenCategoriaController;
use App\Http\Controllers\Medico\DetalleExamenController;
use App\Http\Controllers\Medico\DetalleResultadoExamenController;
use App\Http\Controllers\Medico\EsquemaVacunaController;
use App\Http\Controllers\Medico\EstadoExamenController;
use App\Http\Controllers\Medico\EstadoSolicitudExamenController;
use App\Http\Controllers\Medico\ExamenController;
use App\Http\Controllers\Medico\IdentidadGeneroController;
use App\Http\Controllers\Medico\OrientacionSexualController;
use App\Http\Controllers\Medico\RegistroEmpleadoExamenController;
use App\Http\Controllers\Medico\ReligionController;
use App\Http\Controllers\Medico\ResultadoExamenController;
use App\Http\Controllers\Medico\TipoAntecedenteController;
use App\Http\Controllers\Medico\TipoExamenController;
use App\Http\Controllers\Medico\TipoVacunaController;
use Illuminate\Support\Facades\Route;

// Generar GET - POST - PUT - DELETE
Route::apiResources(
    [
        'tipos-examenes' => TipoExamenController::class,
        'examenes' => ExamenController::class,
        'categorias-examenes' => CategoriaExamenController::class,
        'estados-examenes' => EstadoExamenController::class,
        'detalles-examenes' => DetalleExamenController::class,
        'configuraciones-examenes-categ' => ConfiguracionExamenCategoriaController::class,
        'registros-empleados-examenes' => RegistroEmpleadoExamenController::class,
        'estados-solicitudes-examenes' => EstadoSolicitudExamenController::class,
        'configuraciones-examenes-campos' => ConfiguracionExamenCampoController::class,
        'resultados-examenes-campos' => ResultadoExamenController::class,
        'detalles-resultados-examenes' => DetalleResultadoExamenController::class,
        'tipos-vacunas' => TipoVacunaController::class,
        'esquemas-vacunas' => EsquemaVacunaController::class,
        'religiones' => ReligionController::class,
        'orientaciones-sexuales' => OrientacionSexualController::class,
        'identidades-generos' => IdentidadGeneroController::class,
        'tipos-antecedentes' => TipoAntecedenteController::class,
    ],
    [
        'parameters' => [],

    ]
);

Route::middleware('auth:sanctum')->group(function () {
});
