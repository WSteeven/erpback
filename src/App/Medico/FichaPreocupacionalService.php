<?php

namespace Src\App\Medico;

use App\Http\Requests\Medico\FichaPreocupacionalRequest;
use App\Models\Empleado;
use App\Models\Medico\AntecedenteGinecoObstetrico;
use App\Models\Medico\AntecedentePersonal;
use App\Models\Medico\AntecedenteTrabajoAnterior;
use App\Models\Medico\ExamenRealizado;
use App\Models\Medico\FichaPreocupacional;
use App\Models\Medico\RegistroEmpleadoExamen;
use App\Models\Medico\RiesgoAntecedenteEmpleoAnterior;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class FichaPreocupacionalService
{
    private FichaPreocupacional $ficha;
    private PolymorphicMedicoModelsService $servicioPolimorfico;

    public function __construct(FichaPreocupacional $ficha_preocupacional)
    {
        $this->ficha = $ficha_preocupacional;
        $this->servicioPolimorfico = new PolymorphicMedicoModelsService();
    }

    /**
     * @throws Throwable
     */
    public function crearOActualizarDatosFichaPreocupacional(FichaPreocupacionalRequest $request)
    {
        try {
            $this->insertarAntecedentePersonal($request);
            $this->insertarExamenesRealizados($request->examenes_realizados);
            $this->agregarAntecedentesEmpleosAnteriores($request->antecedentes_empleos_anteriores);
            $this->servicioPolimorfico->syncronizarInformacionFicha($this->ficha, $request);
        } catch (Throwable $th) {
            Log::channel('testing')->info('Log', ['Error guardar datos ficha preocupacional', $th->getLine(), $th->getMessage()]);
            throw $th;
        }
    }

    /**
     * @throws Throwable
     */
    public function insertarExamenesRealizados(array|null $examenes)
    {
        try {
            DB::beginTransaction();

            // Obtener los exámenes ya registrados para la ficha actual
            $examenesExistentes = ExamenRealizado::where('ficha_preocupacional_id', $this->ficha->id)->get();

            $idsEnviados = [];
            if (!is_null($examenes))
                foreach ($examenes as $examen) {
                    // Registrar para comparar luego
                    $idsEnviados[] = $examen['examen_id'];

                    ExamenRealizado::updateOrCreate(
                        [
                            'examen_id' => $examen['examen_id'],
                            'ficha_preocupacional_id' => $this->ficha->id,
                        ],
                        [
                            'tiempo' => $examen['tiempo'],
                            'resultado' => $examen['resultado'],
                        ]
                    );
                }

            // Eliminar exámenes que ya no están en la solicitud
            foreach ($examenesExistentes as $examenExistente) {
                if (!in_array($examenExistente->examen_id, $idsEnviados))
                    $examenExistente->delete();
            }

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['Error insertarExamenesRealizados', $e->getLine(), $e->getMessage()]);
            throw $e;
        }
    }


    /**
     * @throws Throwable
     */
    public function agregarAntecedentesEmpleosAnteriores(array|null $antecedentes)
    {
        if (empty($antecedentes)) {
            return;
        }
        try {
            DB::beginTransaction();

            $idsEmpresasActuales = [];

            foreach ($antecedentes as $antecedente) {
                $empresa = $antecedente['empresa'];
                $idsEmpresasActuales[] = $empresa;

                //Busca o crea el antecedente
                $antecedenteCreado = AntecedenteTrabajoAnterior::updateOrCreate(
                    [
                        'empresa' => $antecedente['empresa'],
                        'ficha_preocupacional_id' => $this->ficha->id
                    ],
                    [
                        'puesto_trabajo' => $antecedente['puesto_trabajo'],
                        'actividades' => $antecedente['actividades'],
                        'tiempo_trabajo' => $antecedente['tiempo_trabajo'],
                        'observacion' => $antecedente['observaciones'],
                    ]
                );

                $antecedenteCreado->riesgos()->delete();

                foreach ($antecedente['tipos_riesgos_ids'] as $riesgo) {
                    RiesgoAntecedenteEmpleoAnterior::create([
                        'tipo_riesgo_id' => $riesgo,
                        'antecedente_id' => $antecedenteCreado->id
                    ]);
                }
            }

            // Eliminar antecedentes no presentes en el nuevo array
            AntecedenteTrabajoAnterior::where('ficha_preocupacional_id', $this->ficha->id)
                ->whereNotIn('empresa', $idsEmpresasActuales)
                ->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error en agregarAntecedentesEmpleosAnteriores', $e->getLine(), $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * @throws Throwable
     */
    public function insertarAntecedentePersonal($request)
    {
        try {
            DB::beginTransaction();
            $antecedente = AntecedentePersonal::updateOrCreate(
                ['ficha_preocupacional_id' => $this->ficha->id],
                [
                    'vida_sexual_activa' => $request->antecedente_personal['vida_sexual_activa'],
                    'hijos_vivos' => $request->antecedente_personal['hijos_vivos'],
                    'hijos_muertos' => $request->antecedente_personal['hijos_muertos'],
                    'tiene_metodo_planificacion_familiar' => $request->antecedente_personal['tiene_metodo_planificacion_familiar'],
                    'tipo_metodo_planificacion_familiar' => $request->antecedente_personal['tipo_metodo_planificacion_familiar'],
                ]);

            $registro_empleado = RegistroEmpleadoExamen::find($request->registro_empleado_examen_id);
            $genero = $registro_empleado->empleado?->genero;
            if ($genero === Empleado::FEMENINO) {
                $this->insertarAntecedentesGinecoObstetricos($request->antecedentes_gineco_obstetricos, $antecedente->id);
            }

            DB::commit();
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['Error insertarAntecedentePersonal', $e->getLine(), $e->getMessage()]);
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @throws Throwable
     */
    public function insertarAntecedentesGinecoObstetricos($data, $antecedente_id)
    {
        Log::channel('testing')->info('Log', ['gineco', $data]);
        try {
            AntecedenteGinecoObstetrico::updateOrCreate(
                [
                    'antecedente_personal_id' => $antecedente_id,
                ],
                [
                    'menarquia' => $data['menarquia'],
                    'ciclos' => $data['ciclos'],
                    'fecha_ultima_menstruacion' => $data['fecha_ultima_menstruacion'],
                    'gestas' => $data['gestas'],
                    'partos' => $data['partos'],
                    'cesareas' => $data['cesareas'],
                    'abortos' => $data['abortos'],
                ]);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['Error insertarAntecedentesGinecoObstetricos', $e->getLine(), $e->getMessage()]);
            throw $e;
        }
    }

}
