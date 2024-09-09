<?php

namespace Src\App\Medico;

use App\Http\Requests\Medico\FichaPeriodicaRequest;
use App\Http\Requests\Medico\FichaReintegroRequest;
use App\Models\Medico\AccidenteEnfermedadLaboral;
use App\Models\Medico\DetalleCategFactorRiesgoFrPuestoTrabAct;
use App\Models\Medico\FichaPreocupacional;
use App\Models\Medico\FichaPeriodica;
use App\Models\Medico\FichaReintegro;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FichaReintegroService
{
    // private $ficha_preocupacional_id;
    private $ficha;
    private $servicioPolimorfico;

    public function __construct(FichaReintegro $ficha)
    {
        $this->ficha = $ficha;
        $this->servicioPolimorfico = new PolymorphicMedicoModelsService();
    }

    public function guardarDatosFichaReintegro(FichaReintegroRequest $request)
    {
        try {
            // $this->servicioPolimorfico->crearAntecedenteClinico($this->ficha, $request->antecedente_clinico_quirurgico);
            // $this->servicioPolimorfico->crearHabitosToxicos($this->ficha, $request->habitos_toxicos);
            // $this->servicioPolimorfico->crearActividadesFisicas($this->ficha, $request->actividades_fisicas);
            // $this->servicioPolimorfico->crearMedicaciones($this->ficha, $request->medicaciones);
            // $this->servicioPolimorfico->crearAccidentesEnfermedadesProfesionales($this->ficha, $request->accidente_trabajo, AccidenteEnfermedadLaboral::ACCIDENTE_TRABAJO);
            // $this->servicioPolimorfico->crearAccidentesEnfermedadesProfesionales($this->ficha, $request->enfermedad_profesional, AccidenteEnfermedadLaboral::ENFERMEDAD_PROFESIONAL);
            // $this->servicioPolimorfico->crearAntecedentesFamiliares($this->ficha, $request->antecedentes_familiares);
            // $this->servicioPolimorfico->crearRevisionesActualesOrganosSistemas($this->ficha, $request->revisiones_actuales_organos_sistemas);
            $this->servicioPolimorfico->crearFactoresRiesgoPuestoTrabajoActual($this->ficha, $request->factores_riesgo_puesto_actual);
            $this->servicioPolimorfico->crearConstanteVital($this->ficha, $request->constante_vital);
            $this->servicioPolimorfico->crearExamenesFisicosRegionales($this->ficha, $request->examenes_fisicos_regionales);
            $this->servicioPolimorfico->crearAptitudMedica($this->ficha, $request->aptitud_medica);
            // $this->servicioPolimorfico->crearDiagnosticosFicha($this->ficha, $request->diagnosticos);
        } catch (\Throwable $th) {
            Log::channel('testing')->info('Log', ['Error guardar datos ficha reintegro', $th->getLine(), $th->getMessage()]);
            throw $th;
        }
    }
}
