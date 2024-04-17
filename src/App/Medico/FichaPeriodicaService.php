<?php

namespace Src\App\Medico;

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
    private $fr_puesto_trabajo_actual;
    public function __construct(FichaPeriodica $ficha)
    {
        $this->ficha = $ficha;
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

    public function agregarActividadesFisicas(array $estilos_vida)
    {
        try {
            foreach ($estilos_vida as $key => $value) {
                DB::beginTransaction();
                $this->ficha->actividadesFisicas()->create(
                    [
                        'nombre_actividad' => $value['nombre_actividad'],
                        'tiempo' => $value['tiempo'],
                    ]
                );
                DB::commit();
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['Error agregarActividadesFisicas', $e->getLine(), $e->getMessage()]);
            throw $e;
        }
    }
    public function agregarMedicaciones(array $medicaciones)
    {
        try {
            foreach ($medicaciones as $key => $value) {
                DB::beginTransaction();
                $this->ficha->medicaciones()->create(
                    [
                        'nombre' => $value['nombre'],
                        'cantidad' => $value['cantidad'],
                    ]
                );
                DB::commit();
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['Error agregarMedicaciones', $e->getLine(), $e->getMessage()]);
            throw $e;
        }
    }
    public function agregarAntecedentesFamiliares(array $antecedentes_familiares)
    {
        try {
            foreach ($antecedentes_familiares as $key => $value) {
                DB::beginTransaction();
                $this->ficha->antecedentesFamiliares()->create(
                    [
                        'descripcion' => $value['descripcion'],
                        'tipo_antecedente_familiar_id' => $value['tipo_antecedente_familiar'],
                        'parentesco' => $value['parentesco'],
                    ]
                );
                DB::commit();
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['Error agregarAntecedentesFamiliares', $e->getLine(), $e->getMessage()]);
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
