<?php

namespace Src\App\Medico;

use App\Http\Requests\Medico\FichaPreocupacionalRequest;
use App\Models\Empleado;
use App\Models\Medico\ActividadFisica;
use App\Models\Medico\AntecedenteFamiliar;
use App\Models\Medico\AntecedenteGinecoObstetrico;
use App\Models\Medico\AntecedentePersonal;
use App\Models\Medico\AntecedenteTrabajoAnterior;
use App\Models\Medico\DescripcionAntecedenteTrabajo;
use App\Models\Medico\DetalleCategFactorRiesgoFrPuestoTrabAct;
use App\Models\Medico\ExamenRealizado;
use App\Models\Medico\Medicacion;
use App\Models\Medico\FichaPreocupacional;
use App\Models\Medico\FichaPeriodica;
use App\Models\Medico\FrPuestoTrabajoActual;
use App\Models\Medico\RegistroEmpleadoExamen;
use App\Models\Medico\ResultadoExamenPreocupacional;
use App\Models\Medico\ResultadoHabitoToxico;
use App\Models\Medico\RiesgoAntecedenteEmpleoAnterior;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FichaPreocupacionalService
{
    private $ficha;
    private $servicioPolimorfico;

    public function __construct(FichaPreocupacional $ficha_preocupacional)
    {
        $this->ficha = $ficha_preocupacional;
        $this->servicioPolimorfico = new PolymorphicMedicoModelsService();
    }

    public function guardarDatosFichaPreocupacional(FichaPreocupacionalRequest $request)
    {
        try {
            //code...
            $this->servicioPolimorfico->crearAntecedenteClinico($this->ficha, $request->antecedente_clinico_quirurgico);
            $this->insertarAntecedentePersonal($request);
            $this->insertarExamenesRealizados($request->examenesRealizados);
            $this->servicioPolimorfico->crearHabitosToxicos($this->ficha, $request->habitosToxicos);
            $this->servicioPolimorfico->crearActividadesFisicas($this->ficha, $request->actividadesFisicas);
            $this->servicioPolimorfico->crearMedicaciones($this->ficha, $request->medicaciones);
            $this->agregarAntecedentesEmpleosAnteriores($request->antecedentesEmpleosAnteriores);
            $this->servicioPolimorfico->crearAccidentesEnfermedadesProfesionales($this->ficha, $request->accidentesTrabajo);
            $this->servicioPolimorfico->crearAccidentesEnfermedadesProfesionales($this->ficha, $request->enfermedadesProfesionales);
            $this->servicioPolimorfico->crearAntecedentesFamiliares($this->ficha, $request->antecedentesFamiliares);
            $this->servicioPolimorfico->crearFactoresRiesgoPuestoTrabajoActual($this->ficha, $request->factoresRiesgoPuestoActual);
            $this->servicioPolimorfico->crearRevisionesActualesOrganosSistemas($this->ficha, $request->revisionesOrganosSistemas);
            $this->servicioPolimorfico->crearConstanteVital($this->ficha, $request->constanteVital);
            $this->servicioPolimorfico->crearExamenesFisicosRegionales($this->ficha, $request->examenesFisicosRegionales);
            $this->servicioPolimorfico->crearDiagnosticosFicha($this->ficha, $request->diagnosticos);
            $this->servicioPolimorfico->crearAptitudMedica($this->ficha, $request->aptitudMedica);
            // throw new Exception('error provocado');
        } catch (\Throwable $th) {
            Log::channel('testing')->info('Log', ['Error guardar datos ficha preocupacional', $th->getLine(), $th->getMessage()]);
            throw $th;
        }
    }
    public function actualizarDatosFichaPreocupacional(FichaPreocupacionalRequest $request)
    {
        try {
            throw new Exception('Actualizar datos de ficha preocupacional');
        } catch (\Throwable $th) {
            Log::channel('testing')->info('Log', ['Error actualizar datos ficha preocupacional', $th->getLine(), $th->getMessage()]);
            throw $th;
        }
    }
    public function insertarExamenesRealizados(array|null $examenes)
    {
        try {
            DB::beginTransaction();
            if (!is_null($examenes))
                foreach ($examenes as $examen) {
                    ExamenRealizado::create([
                        'examen_id' => $examen['examen_id'],
                        'tiempo' => $examen['tiempo'],
                        'resultado' => $examen['resultado'],
                        'ficha_preocupacional_id' => $this->ficha->id,
                    ]);
                }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['Error insertarExamenesRealizados', $e->getLine(), $e->getMessage()]);
            throw $e;
        }
    }

    public function agregarHabitosToxicos(FichaPreocupacional|FichaPeriodica $ficha, array $habitos_toxicos)
    {
        try {
            foreach ($habitos_toxicos as $key => $value) {
                DB::beginTransaction();
                $ficha->habitosToxicos()->create(
                    [
                        'tipo_habito_toxico_id' => $value['tipo_habito_toxico'],
                        'tiempo_consumo_meses' => $value['tiempo_consumo_meses'],
                        'tiempo_abstinencia_meses' => $value['tiempo_abstinencia_meses'],
                    ]
                );
                DB::commit();
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function agregarAntecedentesEmpleosAnteriores(array|null $antecedentes)
    {
        try {
            if (!is_null($antecedentes))
                foreach ($antecedentes as $key => $antecedente) {
                    DB::beginTransaction();
                    $antecedenteCreado = AntecedenteTrabajoAnterior::create(
                        [
                            'empresa' => $antecedente['empresa'],
                            'puesto_trabajo' => $antecedente['puesto_trabajo'],
                            'actividades'   => $antecedente['actividades'],
                            'tiempo_trabajo' => $antecedente['tiempo_trabajo'],
                            'ficha_preocupacional_id' => $this->ficha->id
                        ]
                    );
                    foreach ($antecedente['riesgos'] as $riesgo) {
                        RiesgoAntecedenteEmpleoAnterior::create([
                            'tipo_riesgo_id' => $riesgo,
                            'antecedente_id' => $antecedenteCreado->id
                        ]);
                    }
                    DB::commit();
                }
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error en agregarAntecedentesEmpleosAnteriores']);
            throw $e;
        }
    }

    public function insertarAntecedentePersonal($request)
    {
        try {
            DB::beginTransaction();
            $antecedente = AntecedentePersonal::create([
                'vida_sexual_activa' => $request->vida_sexual_activa,
                'hijos_vivos' => $request->hijos_vivos,
                'hijos_muertos' => $request->hijos_muertos,
                'tiene_metodo_planificacion_familiar' => $request->tiene_metodo_planificacion_familiar,
                'tipo_metodo_planificacion_familiar' => $request->tipo_metodo_planificacion_familiar,
                'ficha_preocupacional_id' =>  $this->ficha->id,
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
    public function insertarAntecedentesGinecoObstetricos($data, $antecedente_id)
    {
        try {
            DB::beginTransaction();
            AntecedenteGinecoObstetrico::create([
                'menarquia' => $data->menarquia,
                'ciclos' => $data->ciclos,
                'fecha_ultima_menstruacion' => $data->fecha_ultima_menstruacion,
                'gestas' => $data->gestas,
                'partos' => $data->partos,
                'cesareas' => $data->cesareas,
                'abortos' => $data->abortos,
                'antecedente_personal_id' => $antecedente_id,
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['Error insertarAntecedentesGinecoObstetricos', $e->getLine(), $e->getMessage()]);
            throw $e;
        }
    }
    public function insertarAccidenteEnfermedadLaboral(array $datos)
    {
        try {
            DB::beginTransaction();
            $this->ficha->accidentesEnfermedades()->create($datos);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['Error insertarAccidenteEnfermedadLaboral', $e->getLine(), $e->getMessage()]);
            throw $e;
        }
    }
}
