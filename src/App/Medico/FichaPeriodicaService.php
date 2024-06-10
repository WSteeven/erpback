<?php

namespace Src\App\Medico;

use App\Http\Requests\Medico\FichaPeriodicaRequest;
use App\Models\Medico\DetalleCategFactorRiesgoFrPuestoTrabAct;
use App\Models\Medico\FichaPreocupacional;
use App\Models\Medico\FichaPeriodica;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FichaPeriodicaService
{
    // private $ficha_preocupacional_id;
    private $ficha;
    private $servicioPolimorfico;

    public function __construct(FichaPeriodica $ficha)
    {
        $this->ficha = $ficha;
        $this->servicioPolimorfico = new PolymorphicMedicoModelsService();
    }

    public function guardarDatosFichaPeriodica(FichaPeriodicaRequest $request){
        try {
            $this->servicioPolimorfico->crearAntecedenteClinico($this->ficha, $request->antecedente_clinico_quirurgico);
            $this->servicioPolimorfico->crearHabitosToxicos($this->ficha, $request->habitosToxicos);
            $this->servicioPolimorfico->crearActividadesFisicas($this->ficha, $request->actividadesFisicas);
            $this->servicioPolimorfico->crearMedicaciones($this->ficha, $request->medicaciones);
            $this->servicioPolimorfico->crearAccidentesEnfermedadesProfesionales($this->ficha, $request->accidentesTrabajo);
            $this->servicioPolimorfico->crearAccidentesEnfermedadesProfesionales($this->ficha, $request->enfermedadesProfesionales);
            $this->servicioPolimorfico->crearAntecedentesFamiliares($this->ficha, $request->antecedentesFamiliares);
            $this->servicioPolimorfico->crearFactoresRiesgoPuestoTrabajoActual($this->ficha, $request->factoresRiesgoPuestoActual);
            $this->servicioPolimorfico->crearRevisionesActualesOrganosSistemas($this->ficha, $request->revisionesOrganosSistemas);
            $this->servicioPolimorfico->crearConstanteVital($this->ficha, $request->constanteVital);
            $this->servicioPolimorfico->crearExamenesFisicosRegionales($this->ficha, $request->examenesFisicosRegionales);
            $this->servicioPolimorfico->crearDiagnosticosFicha($this->ficha, $request->diagnosticos);
            $this->servicioPolimorfico->crearAptitudMedica($this->ficha, $request->aptitudMedica);
        } catch (\Throwable $th) {
            Log::channel('testing')->info('Log', ['Error guardar datos ficha periodica', $th->getLine(), $th->getMessage()]);
            throw $th;
        }
    }
}
