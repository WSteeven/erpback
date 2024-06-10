<?php

namespace Src\App\Medico;

use App\Http\Requests\Medico\FichaRetiroRequest;
use App\Models\Medico\FichaRetiro;
use App\Models\Medico\RegistroEmpleadoExamen;
use Illuminate\Support\Facades\Log;

class fichaRetiroService
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
            $this->servicioPolimorfico->crearAntecedenteClinico($this->ficha, $request->antecedente_clinico_quirurgico);
            $this->servicioPolimorfico->crearAccidentesEnfermedadesProfesionales($this->ficha, $request->accidentesTrabajo);
            $this->servicioPolimorfico->crearAccidentesEnfermedadesProfesionales($this->ficha, $request->enfermedadesProfesionales);
            $this->servicioPolimorfico->crearConstanteVital($this->ficha, $request->constanteVital);
            $this->servicioPolimorfico->crearExamenesFisicosRegionales($this->ficha, $request->examenesFisicosRegionales);
            $this->servicioPolimorfico->crearDiagnosticosFicha($this->ficha, $request->diagnosticos);
            // throw new Exception('error provocado');
        } catch (\Throwable $th) {
            Log::channel('testing')->info('Log', ['Error guardar datos ficha preocupacional', $th->getLine(), $th->getMessage()]);
            throw $th;
        }
    }

    public function consultarResultadosExamenes(RegistroEmpleadoExamen $registro_empleado_examen)
    {
        return [];//$registro_empleado_examen->fichaRetiro;
    }
}
