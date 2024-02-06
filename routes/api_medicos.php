<?php

use App\Http\Controllers\ExamenesController;
use App\Http\Controllers\Medico\ActividadPuestoTrabajoController;
use App\Http\Controllers\Medico\AntecedenteFamiliarController;
use App\Http\Controllers\Medico\AntecedenteGinecoObstetricoController;
use App\Http\Controllers\Medico\AntecedentePersonalController;
use App\Http\Controllers\Medico\AntecedenteTrabajoAnteriorController;
use App\Http\Controllers\Medico\AptitudMedicaController;
use App\Http\Controllers\Medico\CategoriaExamenController;
use App\Http\Controllers\Medico\CieController;
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
use App\Http\Controllers\Medico\ExamenFisicoRegionalController;
use App\Http\Controllers\Medico\ExamenPreocupacionalController;
use App\Http\Controllers\Medico\FactorRiesgoController;
use App\Http\Controllers\Medico\FichaAptitudController;
use App\Http\Controllers\Medico\HabitoToxicoController;
use App\Http\Controllers\Medico\IdentidadGeneroController;
use App\Http\Controllers\Medico\LaboratorioClinicoController;
use App\Http\Controllers\Medico\MedicacionController;
use App\Http\Controllers\Medico\OrientacionSexualController;
use App\Http\Controllers\Medico\PreocupacionalController;
use App\Http\Controllers\Medico\ProfesionalSaludController;
use App\Http\Controllers\Medico\RegistroEmpleadoExamenController;
use App\Http\Controllers\Medico\ReligionController;
use App\Http\Controllers\Medico\ResultadoExamenController;
use App\Http\Controllers\Medico\RevisionActualOrganoSistemaController;
use App\Http\Controllers\Medico\SistemaOrganicoController;
use App\Http\Controllers\Medico\TipoAntecedenteController;
use App\Http\Controllers\Medico\TipoAntecedenteFamiliarController;
use App\Http\Controllers\Medico\TipoAptitudController;
use App\Http\Controllers\Medico\TipoAptitudMedicaLaboralController;
use App\Http\Controllers\Medico\TipoEvaluacionController;
use App\Http\Controllers\Medico\TipoExamenController;
use App\Http\Controllers\Medico\TipoFactorRiesgoController;
use App\Http\Controllers\Medico\TipoHabitoToxicoController;
use App\Http\Controllers\Medico\TipoVacunaController;
use App\Models\Medico\CategoriaExamenFisico;
use App\Models\Medico\CategoriaFactorRiesgo;
use App\Models\Medico\TipoEvaluacionMedicaRetiro;
use Illuminate\Support\Facades\Route;

// Generar GET - POST - PUT - DELETE
Route::apiResources(
    [
        'actividades-puestos-trabajos'=> ActividadPuestoTrabajoController::class,
        'antecedentes-familiares' => AntecedenteFamiliarController::class,
        'antecedentes-gineco-obstetricos' => AntecedenteGinecoObstetricoController::class,
        'antecedentes-personales'=> AntecedentePersonalController::class,
        'antecedentes-trabajos-anteriores'=> AntecedenteTrabajoAnteriorController::class,
        'aptitudes-medicas'=> AptitudMedicaController::class,
        'categorias-examenes' => CategoriaExamenController::class,
        'categorias-examenes-fisicos' => CategoriaExamenFisico::class,
        'categorias-factores-riesgos' => CategoriaFactorRiesgo::class,
        'configuraciones-examenes-campos' => ConfiguracionExamenCampoController::class,
        'configuraciones-examenes-categ' => ConfiguracionExamenCategoriaController::class,
        'constantes-vitales' => ConstanteVitalController::class,
        'descrip-antecedentes-examenes' => DescripcionAntecedenteTrabajoController::class,
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
        'examenes-preocupacionales' => ExamenPreocupacionalController::class,
        'factores-riesgos' => FactorRiesgoController::class,
        'fichas-aptitudes' => FichaAptitudController::class,
        'habitos-toxicos' => HabitoToxicoController::class,
        'identidades-generos' => IdentidadGeneroController::class,
        'medicaciones' => MedicacionController::class,
        'orientaciones-sexuales' => OrientacionSexualController::class,
        'preocupacionales'=> PreocupacionalController::class,
        'profecionales-salud'=> ProfesionalSaludController::class,
        'registros-empleados-examenes' => RegistroEmpleadoExamenController::class,
        'religiones' => ReligionController::class,
        'resultados-examenes' => ResultadoExamenController::class,
        'revisiones-actuales-organos' => RevisionActualOrganoSistemaController::class,
        'sistemas-organicos' => SistemaOrganicoController::class,
        'tipos-antecedentes' => TipoAntecedenteController::class,
        'laboratorios-clinicos' => LaboratorioClinicoController::class,
        'tipos-antecedentes-familiares' => TipoAntecedenteFamiliarController::class,
        'tipos-aptitudes' => TipoAptitudController::class,
        'tipos-aptidudes-medicas-laborales' => TipoAptitudMedicaLaboralController::class,
        'tipos-evaluaciones' => TipoEvaluacionController::class,
        'tipos-eval-medicas-retiro' => TipoEvaluacionMedicaRetiro::class,
        'tipos-examenes' => TipoExamenController::class,
        'tipos-factores-riesgos' => TipoFactorRiesgoController::class,
        'tipos-habitos-toxicos' => TipoHabitoToxicoController::class,
        'tipos-vacunas' => TipoVacunaController::class,
        'cie' => CieController::class
    ],
    [
        'parameters' => [
            'configuraciones-examenes-categ' => 'configuracion_examen_categoria',
            'tipos_eval_medicas_retiro' => 'tipo_eval_medica_retiro',
            'tipos_evaluacione' => 'tipo_evaluacion',
            'laboratorios-clinicos' => 'laboratorio_clinico',
            'detalles-resultados-examenes' => 'detalle_resultado_examen',
        ],

    ]
);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('archivo-cie', [CieController::class, 'archivoCie']);
});

/*************************
 * Archivos polimorficos
 *************************/
Route::get('detalles-resultados-examenes/files/{detalle_resultado_examen}', [DetalleResultadoExamenController::class, 'indexFiles']);
Route::post('detalles-resultados-examenes/files/{detalle_resultado_examen}', [DetalleResultadoExamenController::class, 'storeFiles']);
