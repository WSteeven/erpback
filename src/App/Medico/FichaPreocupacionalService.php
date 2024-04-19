<?php

namespace Src\App\Medico;

use App\Http\Requests\Medico\FichaPreocupacionalRequest;
use App\Models\Empleado;
use App\Models\Medico\ActividadFisica;
use App\Models\Medico\AntecedenteFamiliar;
use App\Models\Medico\AntecedenteGinecoObstetrico;
use App\Models\Medico\AntecedentePersonal;
use App\Models\Medico\AntecedenteTrabajoAnterior;
use App\Models\Medico\ConstanteVital;
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
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FichaPreocupacionalService
{
    private $ficha_preocupacional_id;
    private $ficha;
    private $antecedente_personal;
    private $fr_puesto_trabajo_actual;
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
            if (!is_null($request->antecedente_clinico_quirurgico)) $this->servicioPolimorfico->crearAntecedenteClinico($this->ficha, $request->antecedente_clinico_quirurgico);
            $this->insertarAntecedentePersonal($request);
            $this->insertarExamenesRealizados($request->examenesRealizados);
            $this->servicioPolimorfico->crearHabitosToxicos($this->ficha, $request->habitosToxicos);
            $this->servicioPolimorfico->crearActividadesFisicas($this->ficha, $request->actividadesFisicas);
            $this->servicioPolimorfico->crearMedicaciones($this->ficha, $request->medicaciones);
            $this->agregarAntecedentesEmpleosAnteriores($request->antecedentesEmpleosAnteriores);
            $this->servicioPolimorfico->crearAccidentesEnfermedadesProfesionales($this->ficha, $request->accidentesTrabajo);
            $this->servicioPolimorfico->crearAccidentesEnfermedadesProfesionales($this->ficha, $request->enfermedadesProfesionales);
            $this->servicioPolimorfico->crearAntecedentesFamiliares($this->ficha, $request->antecedentesFamiliares);
            $this->servicioPolimorfico->crearFactoresRiesgoPuestoTrabajoActual($this->ficha, $request->factoresRiesgo);
            $this->servicioPolimorfico->crearDiagnosticosFicha($this->ficha, $request->diagnosticos);
            new Exception('error provocado');
        } catch (\Throwable $th) {
            Log::channel('testing')->info('Log', ['Error guardar datos ficha preocupacional', $th->getLine(), $th->getMessage()]);
            throw $th;
        }
    }
    public function actualizarDatosFichaPreocupacional(FichaPreocupacionalRequest $request)
    {
        try {
            new Exception('Actualizar datos de ficha preocupacional');
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
    public function actualizarHabitosToxicos(array $habitos_toxicos)
    {
        try {
            foreach ($habitos_toxicos as $key => $value) {
                DB::beginTransaction();
                $resultado_habito_toxico = ResultadoHabitoToxico::find($value['id']);
                $resultado_habito_toxico->update(
                    [
                        'tipo_habito_toxico_id' => $value['tipo_habito_toxico'],
                        'tiempo_consumo_meses' => $value['tiempo_consumo_meses'],
                        'tiempo_abstinencia_meses' => $value['tiempo_abstinencia_meses'],
                        'ficha_preocupacional_id' => $this->ficha_preocupacional_id
                    ]
                );
                DB::commit();
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function agregarAntecedentesFamiliares(array $antecedentes_familiares)
    {
        try {
            foreach ($antecedentes_familiares as $key => $value) {
                DB::beginTransaction();
                AntecedenteFamiliar::create(
                    [
                        'descripcion' => $value['descripcion'],
                        'tipo_antecedente_familiar_id' => $value['tipo_antecedente_familiar'],
                        'ficha_preocupacional_id' => $this->ficha_preocupacional_id
                    ]
                );
                DB::commit();
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function actualizarAntecedentesFamiliares(array $atecedentes_personales)
    {
        try {
            foreach ($atecedentes_personales as $key => $value) {
                DB::beginTransaction();
                $antecedente_familiar =  AntecedenteFamiliar::find($value['id']);
                $antecedente_familiar->update(
                    [
                        'descripcion' => $value['descripcion'],
                        'tipo_antecedente_familiar_id' => $value['tipo_antecedente_familiar'],
                        'ficha_preocupacional_id' => $this->ficha_preocupacional_id
                    ]
                );
                DB::commit();
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function agregarFrPuestosTrabajo(array $fr_puestos_trabajos_actuales)
    {
        try {
            foreach ($fr_puestos_trabajos_actuales as $key => $fr_puesto_trabajo_actual) {
                DB::beginTransaction();
                $this->insertarFrPuestoTrabajo($fr_puesto_trabajo_actual);
                $this->agregarDetalleCategFactorRiesgoFrPuestoTrabajoAct($fr_puesto_trabajo_actual['detalle_categ_factor_riesg_fr_puest_trab_act']);
                DB::commit();
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function actualizarFrPuestosTrabajo(array $fr_puestos_trabajos_actuales)
    {
        try {
            foreach ($fr_puestos_trabajos_actuales as $key => $fr_puesto_trabajo_actual) {
                DB::beginTransaction();
                $this->insertarFrPuestoTrabajo($fr_puesto_trabajo_actual['fr_puesto_trabajo_actual']);
                $this->actualizarDetalleCategFactorRiesgoFrPuestoTrabajoAct($fr_puesto_trabajo_actual['detalle_categ_factor_riesg_fr_puest_trab_act']);
                DB::commit();
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function insertarFrPuestoTrabajo(array $fr_puesto_trabajo_actual)
    {
        try {
            DB::beginTransaction();
            $fr_puesto_trabajo = $this->ficha->frPuestoTrabajoActual()->create($fr_puesto_trabajo_actual);
            $this->fr_puesto_trabajo_actual = $fr_puesto_trabajo;
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['Error insertarFrPuestoTrabajo', $e->getLine(), $e->getMessage()]);
            throw $e;
        }
    }
    public function agregarDetalleCategFactorRiesgoFrPuestoTrabajoAct(array $detalles_categorias_factores_riesgos_fr_puesto_trabajo_actual)
    {
        try {
            foreach ($detalles_categorias_factores_riesgos_fr_puesto_trabajo_actual as $key => $categoria_factor_riesgo_id) {
                DB::beginTransaction();
                DetalleCategFactorRiesgoFrPuestoTrabAct::create(
                    [
                        'categoria_factor_riesgo_id' => $categoria_factor_riesgo_id,
                        'fr_puesto_trabajo_actual_id' => $this->fr_puesto_trabajo_actual->id
                    ]
                );
                DB::commit();
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['Error agregarDetalleCategFactorRiesgoFrPuestoTrabajoAct', $e->getLine(), $e->getMessage()]);
            throw $e;
        }
    }
    public function actualizarDetalleCategFactorRiesgoFrPuestoTrabajoAct(array $detalles_categorias_factores_riesgos_fr_puesto_trabajo_actual)
    {
        try {
            foreach ($detalles_categorias_factores_riesgos_fr_puesto_trabajo_actual as $key => $categoria_factor_riesgo_id) {
                DB::beginTransaction();
                $categoria_factor_riesgo_fr_puesto_trabajo_actual = DetalleCategFactorRiesgoFrPuestoTrabAct::find($categoria_factor_riesgo_id);
                $categoria_factor_riesgo_fr_puesto_trabajo_actual->update(
                    [
                        'categoria_factor_riesgo_id' => $categoria_factor_riesgo_id,
                        'fr_puesto_trabajo_actual_id' => $this->fr_puesto_trabajo_actual->id
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
                    $this->crearAntecedenteTrabajoAnterior($antecedente);
                }
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function crearAntecedenteTrabajoAnterior(array $data)
    {
        try {
            DB::beginTransaction();
            AntecedenteTrabajoAnterior::create(
                [
                    'empresa' => $data['empresa'],
                    'puesto_trabajo' => $data['puesto_trabajo'],
                    'actividades_desempenaba'   => $data['actividades_desempenaba'],
                    'tiempo_trabajo_meses' => $data['tiempo_trabajo_meses'],
                    'r_fisico' => $data['r_fisico'],
                    'r_mecanico' => $data['r_mecanico'],
                    'r_quimico' => $data['r_quimico'],
                    'r_biologico' => $data['r_biologico'],
                    'r_ergonomico' => $data['r_ergonomico'],
                    'r_phisosocial' => $data['r_phisosocial'],
                    'observacion' => $data['observacion'],
                    'ficha_preocupacional_id' => $this->ficha->id
                ]
            );
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function actualizarAntecedentesEmpleosAnteriores(array $antecedentes_empleos_anteriores)
    {
        try {
            foreach ($antecedentes_empleos_anteriores as $key => $value) {
                DB::beginTransaction();
                $antecedente_trabajo = AntecedenteTrabajoAnterior::find($value['id']);
                $antecedente_trabajo->update(
                    [
                        'empresa' => $value['empresa'],
                        'puesto_trabajo' => $value['puesto_trabajo'],
                        'actividades_desempenaba'   => $value['actividades_desempenaba'],
                        'tiempo_trabajo_meses' => $value['tiempo_trabajo_meses'],
                        'r_fisico' => $value['r_fisico'],
                        'r_mecanico' => $value['r_mecanico'],
                        'r_quimico' => $value['r_quimico'],
                        'r_biologico' => $value['r_biologico'],
                        'r_ergonomico' => $value['r_ergonomico'],
                        'r_phisosocial' => $value['r_phisosocial'],
                        'observacion' => $value['observacion'],
                        'ficha_preocupacional_id' => $this->ficha_preocupacional_id
                    ]
                );
                DB::commit();
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function agregarExamenes(array $examenes)
    {
        try {
            foreach ($examenes as $key => $value) {
                DB::beginTransaction();
                ResultadoExamenPreocupacional::create(
                    [
                        'tiempo' => $value['tiempo'],
                        'resultados' => $value['resultados'],
                        'genero' => $value['genero'],
                        'antecedente_personal_id' =>   $this->antecedente_personal->id,
                        'ficha_preocupacional_id' => $this->ficha_preocupacional_id

                    ]
                );
                DB::commit();
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function actualizarExamenes(array $examenes)
    {
        try {
            foreach ($examenes as $key => $value) {
                DB::beginTransaction();
                $resultado_examen_preocupacional = ResultadoExamenPreocupacional::find($value['id']);
                $resultado_examen_preocupacional->update(
                    [
                        'tiempo' => $value['tiempo'],
                        'resultados' => $value['resultados'],
                        'genero' => $value['genero'],
                        'antecedente_personal_id' =>   $this->antecedente_personal->id,
                        'ficha_preocupacional_id' => $this->ficha_preocupacional_id

                    ]
                );
                DB::commit();
            }
        } catch (Exception $e) {
            DB::rollBack();
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
                $this->insertarAntecedentesGinecoObstetricos($request, $antecedente->id);
            }

            DB::commit();
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['Error insertarAntecedentePersonal', $e->getLine(), $e->getMessage()]);
            DB::rollBack();
            throw $e;
        }
    }
    public function insertarAntecedentesGinecoObstetricos($request, $antecedente_id)
    {
        try {
            DB::beginTransaction();
            AntecedenteGinecoObstetrico::create([
                'menarquia' => $request->menarquia,
                'ciclos' => $request->ciclos,
                'fecha_ultima_menstruacion' => $request->fecha_ultima_menstruacion,
                'gestas' => $request->gestas,
                'partos' => $request->partos,
                'cesareas' => $request->cesareas,
                'abortos' => $request->abortos,
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
    // public function insertarDescripcionAntecedenteTrabajo(DescripcionAntecedenteTrabajo $descripcion_antecedente_trabajo)
    // {
    //     try {
    //         DB::beginTransaction();
    //         $descripcion_antecedente_trabajo->ficha_preocupacional_id =  $this->ficha_preocupacional_id;
    //         $descripcion_antecedente_trabajo->save();
    //         DB::commit();
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         throw $e;
    //     }
    // }
    public function insertarConstanteVital(array $constante_vital)
    {
        try {
            DB::beginTransaction();
            $this->ficha->constanteVital()->create($constante_vital);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['Error insertarConstanteVital', $e->getLine(), $e->getMessage()]);
            throw $e;
        }
    }
}
