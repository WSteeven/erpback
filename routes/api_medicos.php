<?php

use App\Http\Controllers\ExamenesController;
use App\Http\Controllers\Medico\ActividadPuestoTrabajoController;
use App\Http\Controllers\Medico\AntecedenteGinecoObstetricoController;
use App\Http\Controllers\Medico\AntecedentePersonalController;
use App\Http\Controllers\Medico\AntecedenteTrabajoAnteriorController;
use App\Http\Controllers\Medico\AptitudMedicaController;
use App\Http\Controllers\Medico\CategoriaExamenController;
use App\Http\Controllers\Medico\ConfiguracionExamenCampoController;
use App\Http\Controllers\Medico\ConfiguracionExamenCategoriaController;
use App\Http\Controllers\Medico\ConstanteVitalController;
use App\Http\Controllers\Medico\DescripcionAntecedenteTrabajoController;
use App\Http\Controllers\Medico\DetalleExamenController;
use App\Http\Controllers\Medico\DetalleResultadoExamenController;
use App\Http\Controllers\Medico\DiagnosticoController;
use App\Http\Controllers\Medico\EsquemaVacunaController;
use App\Http\Controllers\Medico\EstadoExamenController;
use App\Http\Controllers\Medico\EstadoSolicitudExamenController;
use App\Http\Controllers\Medico\EstiloVidaController;
use App\Http\Controllers\Medico\ExamenController;
use App\Http\Controllers\Medico\ExamenEspecificoController;
use App\Http\Controllers\Medico\ExamenExamenPreocupacionalController;
use App\Http\Controllers\Medico\ExamenFisicoRegionalController;
use App\Http\Controllers\Medico\FactorRiesgoController;
use App\Http\Controllers\Medico\HabitoToxicoController;
use App\Http\Controllers\Medico\IdentidadGeneroController;
use App\Http\Controllers\Medico\MedicacionController;
use App\Http\Controllers\Medico\OrientacionSexualController;
use App\Http\Controllers\Medico\PreocupacionalController;
use App\Http\Controllers\Medico\RegistroEmpleadoExamenController;
use App\Http\Controllers\Medico\ReligionController;
use App\Http\Controllers\Medico\ResultadoExamenController;
use App\Http\Controllers\Medico\RevisionActualOrganoSistemaController;
use App\Http\Controllers\Medico\SistemaOrganicoController;
use App\Http\Controllers\Medico\TipoAntecedenteController;
use App\Http\Controllers\Medico\TipoAntecedenteFamiliarController;
use App\Http\Controllers\Medico\TipoAptitudController;
use App\Http\Controllers\Medico\TipoExamenController;
use App\Http\Controllers\Medico\TipoFactorRiesgoController;
use App\Http\Controllers\Medico\TipoHabitoToxicoController;
use App\Http\Controllers\Medico\TipoVacunaController;
use App\Models\Medico\CategoriaExamenFisico;
use App\Models\Medico\CategoriaFactorRiesgo;
use Illuminate\Support\Facades\Route;

// Generar GET - POST - PUT - DELETE
Route::apiResources(
    [
        'actividades-puesto-trabajo', ActividadPuestoTrabajoController::class,
        'antecedentes-familiares', TipoAntecedenteFamiliarController::class,
        'antecedentes-gineco-obstetricos', AntecedenteGinecoObstetricoController::class,
        'antecedentes-personales', AntecedentePersonalController::class,
        'antecedentes-trabajos-anteriores', AntecedenteTrabajoAnteriorController::class,
        'aptitudes-medicas', AptitudMedicaController::class,
        'categorias-examenes' => CategoriaExamenController::class,
        'categorias-examenes-fisicos' => CategoriaExamenFisico::class,
        'categorias-factores-riesgos' => CategoriaFactorRiesgo::class,
        'configuraciones-examenes-campos' => ConfiguracionExamenCampoController::class,
        'configuraciones-examenes-categoria' => ConfiguracionExamenCategoriaController::class,
        'constantes-vitales' => ConstanteVitalController::class,
        'descripciones-antecedentes-examenes' => DescripcionAntecedenteTrabajoController::class,
        'detalles-examenes' => DetalleExamenController::class,
        'detalles-resultados-examenes' => DetalleResultadoExamenController::class,
        'diagnosticos' => DiagnosticoController::class,
        'esquemas-vacunas' => EsquemaVacunaController::class,
        'estados-examenes' => EstadoExamenController::class,
        'estados-solicitudes-examenes' => EstadoSolicitudExamenController::class,
        'estilos-vida' => EstiloVidaController::class,
        'examenes' => ExamenController::class,
        'examenes-especificos' => ExamenEspecificoController::class,
        'examenes-fisicos-regionales' => ExamenFisicoRegionalController::class,
        'examenes-preocupacionales' => ExamenExamenPreocupacionalController::class,
        'factores-riesgo' => FactorRiesgoController::class,
        'habitos-toxicos' => HabitoToxicoController::class,
        'identidades-generos' => IdentidadGeneroController::class,
        'medicaciones' => MedicacionController::class,
        'orientaciones-sexuales' => OrientacionSexualController::class,
        'preocupacionales', PreocupacionalController::class,
        'registros-empleados-examenes' => RegistroEmpleadoExamenController::class,
        'religiones' => ReligionController::class,
        'resultados-examenes' => ResultadoExamenController::class,
        'revisiones-actuales-organos' => RevisionActualOrganoSistemaController::class,
        'sistemas-organicos' => SistemaOrganicoController::class,
        'tipos-antecedentes' => TipoAntecedenteController::class,
        'tipos-antecedentes-familiares' => TipoAntecedenteFamiliarController::class,
        'tipos-aptitudes' => TipoAptitudController::class,
        'tipos-examenes' => TipoExamenController::class,
        'tipos-examenes' => TipoExamenController::class,
        'tipos-factores-riesgos' => TipoFactorRiesgoController::class,
        'tipos-habitos-toxicos' => TipoHabitoToxicoController::class,
        'tipos-vacunas' => TipoVacunaController::class,
    ],
    [
        'parameters' => [
            'configuraciones-examenes-categ' => 'configuracion_examen_categoria'
        ],

    ]
);

Route::middleware('auth:sanctum')->group(function () {
});
