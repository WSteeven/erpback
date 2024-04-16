<?php

namespace Src\App\Medico;

use App\Models\Medico\ActividadFisica;
use App\Models\Medico\AntecedenteFamiliar;
use App\Models\Medico\AntecedenteGinecoObstetrico;
use App\Models\Medico\AntecedentePersonal;
use App\Models\Medico\AntecedenteTrabajoAnterior;
use App\Models\Medico\ConstanteVital;
use App\Models\Medico\DescripcionAntecedenteTrabajo;
use App\Models\Medico\DetalleCategFactorRiesgoFrPuestoTrabAct;
use App\Models\Medico\Medicacion;
use App\Models\Medico\FichaPreocupacional;
use App\Models\Medico\FichaPeriodica;
use App\Models\Medico\FrPuestoTrabajoActual;
use App\Models\Medico\ResultadoExamenPreocupacional;
use App\Models\Medico\ResultadoHabitoToxico;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FichaPreocupacionalService
{
    private $ficha_preocupacional_id;
    private $preocupacional;
    private $antecedente_personal;
    private $fr_puesto_trabajo_actual;
    public function __construct($ficha_preocupacional_id)
    {
        $this->ficha_preocupacional_id = $ficha_preocupacional_id;
        $this->preocupacional = FichaPreocupacional::find($ficha_preocupacional_id);
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
    public function agregarActividadesFisicas(array $estilos_vida)
    {
        try {
            foreach ($estilos_vida as $key => $value) {
                DB::beginTransaction();
                ActividadFisica::create(
                    [
                        'nombre_actividad' => $value['nombre_actividad'],
                        'tiempo' => $value['tiempo'],
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
    public function actualizarActividadesFisicas(array $actividades_fisicas)
    {
        try {
            foreach ($actividades_fisicas as $key => $value) {
                DB::beginTransaction();
                $actividad_fisica = ActividadFisica::find($value['id']);
                $actividad_fisica->update(
                    [
                        'nombre_actividad' => $value['nombre_actividad'],
                        'tiempo' => $value['tiempo'],
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
    public function agregarMedicaciones(array $medicaciones)
    {
        try {
            foreach ($medicaciones as $key => $value) {
                DB::beginTransaction();
                Medicacion::create(
                    [
                        'nombre' => $value['nombre'],
                        'cantidad' => $value['cantidad'],
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
    public function actualizarMedicaciones(array $medicaciones)
    {
        try {
            foreach ($medicaciones as $key => $value) {
                DB::beginTransaction();
                $medicacion = Medicacion::find($value['id']);
                $medicacion->update(
                    [
                        'nombre' => $value['nombre'],
                        'cantidad' => $value['cantidad'],
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
                $this->insertarFrPuestoTrabajo(
                    new FrPuestoTrabajoActual([
                        'puesto_trabajo' => $fr_puesto_trabajo_actual['puesto_trabajo'],
                        'actividad' => $fr_puesto_trabajo_actual['actividad'],
                        'medidas_preventivas' => $fr_puesto_trabajo_actual['medidas_preventivas']
                    ])
                );
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
    public function insertarFrPuestoTrabajo(FrPuestoTrabajoActual $fr_puesto_trabajo_actual)
    {
        try {
            DB::beginTransaction();
            $fr_puesto_trabajo_actual->ficha_preocupacional_id =  $this->ficha_preocupacional_id;
            $fr_puesto_trabajo_actual->save();
            $this->fr_puesto_trabajo_actual = $fr_puesto_trabajo_actual;
            DB::commit();
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['Error insertarFrPuestoTrabajo', $e->getLine(), $e->getMessage()]);
            DB::rollBack();
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
    public function agregarAntecedentesEmpleosAnteriores(array $antecedentes_empleos_anteriores)
    {
        try {
            foreach ($antecedentes_empleos_anteriores as $key => $value) {
                DB::beginTransaction();
                AntecedenteTrabajoAnterior::create(
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
    public function insertarAntecedentePersonal(AntecedentePersonal $antecedente_personal)
    {
        try {
            DB::beginTransaction();
            $antecedente_personal->ficha_preocupacional_id =  $this->ficha_preocupacional_id;
            $antecedente_personal->save();
            $this->antecedente_personal = $antecedente_personal;
            DB::commit();
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['Error insertarAntecedentePersonal', $e->getLine(), $e->getMessage()]);
            DB::rollBack();
            throw $e;
        }
    }
    public function insertarAntecedentesGinecoObstetricos(AntecedenteGinecoObstetrico $antecedente_gineco_obstetrico)
    {
        try {
            DB::beginTransaction();
            $antecedente_gineco_obstetrico->ficha_preocupacional_id =  $this->antecedente_personal->id;
            $antecedente_gineco_obstetrico->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function insertarDescripcionAntecedenteTrabajo(DescripcionAntecedenteTrabajo $descripcion_antecedente_trabajo)
    {
        try {
            DB::beginTransaction();
            $descripcion_antecedente_trabajo->ficha_preocupacional_id =  $this->ficha_preocupacional_id;
            $descripcion_antecedente_trabajo->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function insertarConstanteVital(ConstanteVital $constante_vital)
    {
        try {
            DB::beginTransaction();
            $constante_vital->ficha_preocupacional_id =  $this->ficha_preocupacional_id;
            $constante_vital->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
