<?php

use App\Http\Controllers\Medico\ActividadFisicaController;
use App\Http\Controllers\Medico\AntecedenteFamiliarController;
use App\Http\Controllers\Medico\AntecedenteGinecoObstetricoController;
use App\Http\Controllers\Medico\AntecedentePersonalController;
use App\Http\Controllers\Medico\AntecedenteTrabajoAnteriorController;
use App\Http\Controllers\Medico\AptitudMedicaController;
use App\Http\Controllers\Medico\CategoriaExamenController;
use App\Http\Controllers\Medico\CategoriaExamenFisicoController;
use App\Http\Controllers\Medico\CategoriaFactorRiesgoController;
use App\Http\Controllers\Medico\CieController;
use App\Http\Controllers\Medico\CitaMedicaController;
use App\Http\Controllers\Medico\ConfiguracionCuestionarioEmpleadoController;
use App\Http\Controllers\Medico\ConfiguracionExamenCampoController;
use App\Http\Controllers\Medico\ConfiguracionExamenCategoriaController;
use App\Http\Controllers\Medico\ConstanteVitalController;
use App\Http\Controllers\Medico\ConsultaMedicaController;
use App\Http\Controllers\Medico\CuestionarioController;
use App\Http\Controllers\Medico\DescripcionAntecedenteTrabajoController;
use App\Http\Controllers\Medico\DetalleExamenController;
use App\Http\Controllers\Medico\DetalleResultadoExamenController;
use App\Http\Controllers\Medico\DiagnosticoCitaController;
use App\Http\Controllers\Medico\DiagnosticoRecetaController;
use App\Http\Controllers\Medico\EsquemaVacunaController;
use App\Http\Controllers\Medico\EstadoExamenController;
use App\Http\Controllers\Medico\EstadoSolicitudExamenController;
use App\Http\Controllers\Medico\ExamenController;
use App\Http\Controllers\Medico\ExamenFisicoRegionalController;
use App\Http\Controllers\Medico\ExamenOrganoReproductivoController;
use App\Http\Controllers\Medico\FactorRiesgoController;
use App\Http\Controllers\Medico\FichaAptitudController;
use App\Http\Controllers\Medico\FichaPeriodicaController;
use App\Http\Controllers\Medico\FichaPreocupacionalController;
use App\Http\Controllers\Medico\FichaRetiroController;
use App\Http\Controllers\Medico\IdentidadGeneroController;
use App\Http\Controllers\Medico\LaboratorioClinicoController;
use App\Http\Controllers\Medico\MedicacionController;
use App\Http\Controllers\Medico\OrientacionSexualController;
use App\Http\Controllers\Medico\PreguntaController;
use App\Http\Controllers\Medico\ProfesionalSaludController;
use App\Http\Controllers\Medico\RecetaController;
use App\Http\Controllers\Medico\RegionCuerpoController;
use App\Http\Controllers\Medico\RegistroEmpleadoExamenController;
use App\Http\Controllers\Medico\ReligionController;
use App\Http\Controllers\Medico\RespuestaCuestionarioEmpleadoController;
use App\Http\Controllers\Medico\ResultadoExamenController;
use App\Http\Controllers\Medico\ResultadoExamenPreocupacionalController;
use App\Http\Controllers\Medico\ResultadoHabitoToxicoController;
use App\Http\Controllers\Medico\RevisionActualOrganoSistemaController;
use App\Http\Controllers\Medico\SistemaOrganicoController;
use App\Http\Controllers\Medico\SolicitudExamenController;
use App\Http\Controllers\Medico\TipoAntecedenteController;
use App\Http\Controllers\Medico\TipoAntecedenteFamiliarController;
use App\Http\Controllers\Medico\TipoAptitudController;
use App\Http\Controllers\Medico\TipoAptitudMedicaLaboralController;
use App\Http\Controllers\Medico\TipoEvaluacionController;
use App\Http\Controllers\Medico\TipoEvaluacionMedicaRetiroController;
use App\Http\Controllers\Medico\TipoExamenController;
use App\Http\Controllers\Medico\TipoFactorRiesgoController;
use App\Http\Controllers\Medico\TipoHabitoToxicoController;
use App\Http\Controllers\Medico\TipoVacunaController;
use Illuminate\Support\Facades\Route;

// Generar GET - POST - PUT - DELETE
Route::apiResources(
    [
        // 'actividades-puestos-trabajos' => ActividadPuestoTrabajoController::class,
        'antecedentes-familiares' => AntecedenteFamiliarController::class,
        'antecedentes-gineco-obstetricos' => AntecedenteGinecoObstetricoController::class,
        'antecedentes-personales' => AntecedentePersonalController::class,
        'antecedentes-trabajos-anteriores' => AntecedenteTrabajoAnteriorController::class,
        'aptitudes-medicas' => AptitudMedicaController::class,
        'categorias-examenes' => CategoriaExamenController::class,
        'categorias-examenes-fisicos' => CategoriaExamenFisicoController::class,
        'categorias-factores-riesgos' => CategoriaFactorRiesgoController::class,
        'cie' => CieController::class,
        'citas-medicas' => CitaMedicaController::class,
        'configuraciones-examenes-campos' => ConfiguracionExamenCampoController::class,
        'configuraciones-examenes-categ' => ConfiguracionExamenCategoriaController::class,
        'config-cuestionario-empleado' => ConfiguracionCuestionarioEmpleadoController::class,
        'constantes-vitales' => ConstanteVitalController::class,
        'consultas-medicas' => ConsultaMedicaController::class,
        'descrip-antecedentes-examenes' => DescripcionAntecedenteTrabajoController::class,
        'detalles-examenes' => DetalleExamenController::class,
        'detalles-resultados-examenes' => DetalleResultadoExamenController::class,
        'diagnosticos-citas-medicas' => DiagnosticoCitaController::class,
        'diagnosticos-recetas' => DiagnosticoRecetaController::class,
        'esquemas-vacunas' => EsquemaVacunaController::class,
        'estados-examenes' => EstadoExamenController::class,
        'estados-solicitudes-examenes' => EstadoSolicitudExamenController::class,
        'estilos-vida' => ActividadFisicaController::class,
        'examenes' => ExamenController::class,
        'examenes-organos-reproductivos' => ExamenOrganoReproductivoController::class,
        'examenes-fisicos-regionales' => ExamenFisicoRegionalController::class,
        'examenes-preocupacionales' => ResultadoExamenPreocupacionalController::class,
        'factores-riesgos' => FactorRiesgoController::class,
        'fichas-aptitudes' => FichaAptitudController::class,
        'habitos-toxicos' => ResultadoHabitoToxicoController::class,
        'identidades-generos' => IdentidadGeneroController::class,
        'medicaciones' => MedicacionController::class,
        'orientaciones-sexuales' => OrientacionSexualController::class,
        'profecionales-salud' => ProfesionalSaludController::class,
        'registros-empleados-examenes' => RegistroEmpleadoExamenController::class,
        'religiones' => ReligionController::class,
        'resultados-examenes' => ResultadoExamenController::class,
        'revisiones-actuales-organos' => RevisionActualOrganoSistemaController::class,
        'sistemas-organos' => SistemaOrganicoController::class,
        'tipos-antecedentes' => TipoAntecedenteController::class,
        'laboratorios-clinicos' => LaboratorioClinicoController::class,
        'tipos-antecedentes-familiares' => TipoAntecedenteFamiliarController::class,
        'tipos-aptitudes' => TipoAptitudController::class,
        'tipos-aptitudes-medicas-laborales' => TipoAptitudMedicaLaboralController::class,
        'tipos-evaluaciones' => TipoEvaluacionController::class,
        'tipos-eval-medicas-retiro' => TipoEvaluacionMedicaRetiroController::class,
        'tipos-examenes' => TipoExamenController::class,
        'tipos-factores-riesgos' => TipoFactorRiesgoController::class,
        'tipos-habitos-toxicos' => TipoHabitoToxicoController::class,
        'tipos-vacunas' => TipoVacunaController::class,
        'preguntas' => PreguntaController::class,
        'resp-cuestionarios-empleados' => RespuestaCuestionarioEmpleadoController::class,
        'recetas' => RecetaController::class,
        'solicitudes-examenes' => SolicitudExamenController::class,
        'regiones-cuerpo' => RegionCuerpoController::class,
        'fichas-periodicas' => FichaPeriodicaController::class,
        'fichas-preocupacionales' => FichaPreocupacionalController::class,
        'fichas-retiro' => FichaRetiroController::class,
    ],
    [
        'parameters' => [
            'configuraciones-examenes-categ' => 'configuracion_examen_categoria',
            'tiposeval-medicas-retiro' => 'tipo_eval_medica_retiro',
            'tipos-evaluacione' => 'tipo_evaluacion',
            'laboratorios-clinicos' => 'laboratorio_clinico',
            'resp-cuestionarios-empleados' => 'respuesta_cuestionario_empleado',
            'detalles-resultados-examenes' => 'detalle_resultado_examen',
            'citas-medicas' => 'cita_medica',
            'solicitudes-examenes' => 'solicitud_examen',
            'diagnosticos-recetas' => 'diagnostico_receta',
            'consultas-medicas' => 'consulta_medica',
            'esquemas-vacunas' => 'esquema_vacuna',
            'tipos-eval-medicas-retiro' => 'tipo_evaluacion_medica_retiro',
            'tipos-aptitudes-medicas-laborales' => 'tipo_aptitud_medica_laboral',
            'tipos-antecedentes-familiare' => 'tipo_antecedente_familiar',
            'tipos-antecedentes' => 'tipo_antecedente',
            'tipos-antecedentes-familiares' => 'tipo_antecedente_familiar',
            'tipos-habitos_toxico' => 'tipo_habito_toxico',
            'fichas-aptitudes' => 'ficha_aptitud',
            'regiones-cuerpo' => 'region_cuerpo',
            'registros-empleados-examene' => 'registro_empleado_examen',
            'religiones' => 'religion',
            'resultados-examene' => 'resultado_examen',
            'fichas-periodicas' => 'ficha',
            'fichas-preocupacionales' => 'ficha_preocupacional',
            'orientaciones-sexuales' => 'orientacion_sexual',
            'identidades-generos' => 'identidad_genero',
            'tipos-habitos-toxicos' => 'tipo_habito_toxico',
            'examenes-organos-reproductivos' => 'examen',
        ],

    ]
);

/********
 * Otros
 ********/
Route::post('archivo-cie', [CieController::class, 'archivoCie']);
Route::get('reporte-cuestionario', [CuestionarioController::class, 'reportesCuestionarios']);
Route::get('imprimir-cuestionario', [CuestionarioController::class, 'imprimirCuestionario']);
Route::get('ficha-preocupacional-informacion-defecto', [FichaPreocupacionalController::class, 'consultarInformacionDefectoFicha']);

/************************************
 * Cambiar estados de citas medicas
 ************************************/
Route::post('citas-medicas/cancelar/{cita_medica}', [CitaMedicaController::class, 'cancelar']);
Route::post('citas-medicas/rechazar/{cita_medica}', [CitaMedicaController::class, 'rechazar']);

/*************************
 * Archivos polimorficos
 *************************/
Route::get('detalles-resultados-examenes/files/{detalle_resultado_examen}', [DetalleResultadoExamenController::class, 'indexFiles']);
Route::post('detalles-resultados-examenes/files/{detalle_resultado_examen}', [DetalleResultadoExamenController::class, 'storeFiles']);

Route::get('esquemas-vacunas/files/{esquema_vacuna}', [EsquemaVacunaController::class, 'indexFiles']);
Route::post('esquemas-vacunas/files/{esquema_vacuna}', [EsquemaVacunaController::class, 'storeFiles']);

Route::get('solicitudes-examenes/files/{solicitud_examen}', [SolicitudExamenController::class, 'indexFiles']);
Route::post('solicitudes-examenes/files/{solicitud_examen}', [SolicitudExamenController::class, 'storeFiles']);

/*****************
 * Imprimir PDFs
 *****************/
Route::get('fichas-aptitudes/imprimir/{ficha_aptitud}', [FichaAptitudController::class, 'imprimirPDF']);
Route::get('fichas-retiros/imprimir', [FichaRetiroController::class, 'imprimirPDF']);
Route::get('fichas-preocupacionales/imprimir/{ficha_preocupacional}', [FichaPreocupacionalController::class, 'imprimirPDF']);

Route::put('resultados-examenes', [ResultadoExamenController::class, 'multipleUpdate']);
