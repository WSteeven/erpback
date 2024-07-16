<?php

namespace Src\App\Medico;

use App\Http\Requests\Medico\FichaRetiroRequest;
use App\Models\Medico\AccidenteEnfermedadLaboral;
use App\Models\Medico\FichaRetiro;
use App\Models\Medico\RegistroEmpleadoExamen;
use Illuminate\Support\Facades\Log;

class FichaRetiroService
{
    private $ficha;
    private $servicioPolimorfico;

    public function __construct(FichaRetiro $ficha = null)
    {
        $this->ficha = $ficha;
        $this->servicioPolimorfico = new PolymorphicMedicoModelsService();
    }

    public function guardarDatosFichaRetiro(FichaRetiroRequest $request)
    {
        try {
            //code...
            // $this->servicioPolimorfico->crearAntecedenteClinico($this->ficha, $request->antecedente_clinico_quirurgico);
            $this->servicioPolimorfico->crearAccidentesEnfermedadesProfesionales($this->ficha, $request->accidente_trabajo, AccidenteEnfermedadLaboral::ACCIDENTE_TRABAJO);
            $this->servicioPolimorfico->crearAccidentesEnfermedadesProfesionales($this->ficha, $request->enfermedad_profesional, AccidenteEnfermedadLaboral::ENFERMEDAD_PROFESIONAL);
            $this->servicioPolimorfico->crearConstanteVital($this->ficha, $request->constante_vital);
            $this->servicioPolimorfico->crearExamenesFisicosRegionales($this->ficha, $request->examenes_fisicos_regionales);
            // $this->servicioPolimorfico->crearDiagnosticosFicha($this->ficha, $request->diagnosticos);
            // throw new Exception('error provocado');
        } catch (\Throwable $th) {
            Log::channel('testing')->info('Log', ['Error guardar datos ficha preocupacional', $th->getLine(), $th->getMessage()]);
            throw $th;
        }
    }

    public function consultarResultadosExamenes(RegistroEmpleadoExamen $registro_empleado_examen)
    {
        return []; //$registro_empleado_examen->fichaRetiro;
    }
}
