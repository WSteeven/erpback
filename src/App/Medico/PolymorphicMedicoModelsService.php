<?php

namespace Src\App\Medico;

use App\Models\Medico\DetalleCategFactorRiesgoFrPuestoTrabAct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class PolymorphicMedicoModelsService
{

    /**
     * Esta función crea antecedentes clínicos, uno a la vez.
     * @throws Throwable
     */
    public function crearAntecedenteClinico(Model $entidad, string|array|null $data)
    {
        try {
            DB::beginTransaction();
            if (!is_null($data))
                if (is_array($data))
                    foreach ($data as $d) {
                        $entidad->antecedentesClinicos()->create(['descripcion' => $d]);
                    }
                else
                    $entidad->antecedentesClinicos()->create(['descripcion' => $data]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error en crearAntecedenteClinico']);
            throw $th;
        }
    }

    /**
     * @throws Throwable
     */
    public function crearHabitosToxicos(Model $entidad, array|null $data)
    {
        try {
            DB::beginTransaction();
            if (!is_null($data))
                foreach ($data as $d) {
                    $entidad->habitosToxicos()->create(
                        [
                            'tipo_habito_toxico_id' => $d['tipo_habito_toxico_id'],
                            'tiempo_consumo_meses' => $d['tiempo_consumo_meses'],
                            'cantidad' => $d['cantidad'],
                            'ex_consumidor' => $d['ex_consumidor'],
                            'tiempo_abstinencia_meses' => $d['tiempo_abstinencia_meses'],
                        ]
                    );
                }
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error en crearHabitoToxico']);
            throw $th;
        }
    }

    /**
     * Está función recibe un objeto o un array para guardar una o varias actividades fisicas
     * @throws Throwable
     */
    public function crearActividadesFisicas(Model $entidad, array|null $data)
    {
        try {
            DB::beginTransaction();
            if (!is_null($data))
                foreach ($data as $d) {
                    $entidad->actividadesFisicas()->create([
                        'nombre_actividad' => $d['nombre_actividad'],
                        'tiempo' => $d['tiempo']
                    ]);
                }
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error en crearActividadFisica']);
            throw $th;
        }
    }

    /**
     * Está función recibe un objeto o un array para guardar una o varias medicaciones
     * @throws Throwable
     */
    public function crearMedicaciones(Model $entidad, array|null $data)
    {
        try {
            if (!is_null($data))
                foreach ($data as $d) {
                    DB::beginTransaction();
                    $entidad->medicaciones()->create([
                        'nombre' => $d['nombre'],
                        'cantidad' => $d['cantidad']
                    ]);
                    DB::commit();
                }
        } catch (Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error en crearMedicacion']);
            throw $th;
        }
    }

    /**
     * Está función recibe un objeto o un array para guardar una o varias accidentes o enfermedades profesionales
     * Depende del tipo que reciba en cada objeto de `$data` se guardará como accidente de trabajo o como enfermedad profesional.
     * @throws Throwable
     */
    public function crearAccidentesEnfermedadesProfesionales(Model $entidad, array|null $data, $tipo)
    {
        try {
            DB::beginTransaction();
            $entidad->accidentesEnfermedades()->create([
                'tipo' => $tipo,
                'observacion' => $data['observacion'],
                'calificado_iss' => $data['calificado_iss'],
                'instituto_seguridad_social' => $data['instituto_seguridad_social'],
                'fecha' => $data['fecha'],
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error en crearAccidentesEnfermedadesProfesionales']);
            throw $th;
        }
    }

    /**
     * @throws Throwable
     */
    public function crearAccidentesEnfermedadesProfesionalesOld(Model $entidad, array|null $data, $tipo)
    {
        try {
            DB::beginTransaction();
            if (!is_null($data))
                foreach ($data as $d) {
                    $entidad->accidentesEnfermedades()->create([
                        'tipo' => $tipo,
                        'observacion' => $d['observacion'],
                        'calificado_iss' => $d['calificado_iss'],
                        'instituto_seguridad_social' => $d['instituto_seguridad_social'],
                        'fecha' => $d['fecha'],
                    ]);
                }
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error en crearAccidentesEnfermedadesProfesionales']);
            throw $th;
        }
    }

    /**
     * @throws Throwable
     */
    public function crearAntecedentesFamiliares(Model $entidad, array|null $data)
    {
        try {
            DB::beginTransaction();
            if (!is_null($data))
                foreach ($data as $d) {
                    $entidad->antecedentesFamiliares()->create([
                        'descripcion' => $d['descripcion'],
                        'tipo_antecedente_familiar_id' => $d['tipo_antecedente_familiar_id'],
                        'parentesco' => $d['parentesco'],
                    ]);
                }
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error en crearAntecedentesFamiliares']);
            throw $th;
        }
    }

    /**
     * @throws Throwable
     */
    public function crearFactoresRiesgoPuestoTrabajoActual(Model $entidad, array|null $data)
    {
        try {
            DB::beginTransaction();
            if (!is_null($data))
                foreach ($data as $d) {
                    $factor = $entidad->frPuestoTrabajoActual()->create([
                        'puesto_trabajo' => $d['puesto_trabajo'],
                        'actividad' => $d['actividad'],
                        'tiempo_trabajo' => $d['tiempo_trabajo'] ?? null,
                        'medidas_preventivas' => $d['medidas_preventivas'],
                    ]);
                    foreach ($d['categorias'] as $c) {
                        DetalleCategFactorRiesgoFrPuestoTrabAct::create([
                            'categoria_factor_riesgo_id' => $c,
                            'fr_puesto_trabajo_actual_id' => $factor->id,
                        ]);
                    }
                }
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error en crearFactoresRiesgoPuestoTrabajoActual']);
            throw $th;
        }
    }

    /**
     * @throws Throwable
     */
    public function crearRevisionesActualesOrganosSistemas(Model $entidad, array $data)
    {
        try {
            DB::beginTransaction();
            foreach ($data as $d) {
                $entidad->revisionesActualesOrganosSistemas()->create([
                    'organo_id' => $d['organo_id'],
                    'descripcion' => $d['descripcion'],
                ]);
            }
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error en crearFactoresRiesgoPuestoTrabajoActual']);
            throw $th;
        }
    }

    /**
     * @throws Throwable
     */
    public function crearConstanteVital(Model $entidad, $data)
    {
        try {
            DB::beginTransaction();
            $entidad->constanteVital()->create([
                'presion_arterial' => $data['presion_arterial'],
                'temperatura' => $data['temperatura'],
                'frecuencia_cardiaca' => $data['frecuencia_cardiaca'],
                'saturacion_oxigeno' => $data['saturacion_oxigeno'],
                'frecuencia_respiratoria' => $data['frecuencia_respiratoria'],
                'peso' => $data['peso'],
                'talla' => $data['talla'],
                'indice_masa_corporal' => $data['indice_masa_corporal'],
                'perimetro_abdominal' => $data['perimetro_abdominal'],
            ]);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['Error insertarConstanteVital', $e->getLine(), $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * @throws Throwable
     */
    public function crearExamenesFisicosRegionales(Model $entidad, array|null $data)
    {
        try {
            DB::beginTransaction();
            if (!is_null($data))
                foreach ($data as $d) {
                    $entidad->examenesFisicosRegionales()->create([
                        'categoria_examen_fisico_id' => $d['categoria_examen_fisico_id'],
                        'observacion' => $d['observacion'],
                    ]);
                }
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error en crearExamenesFisicosRegionales']);
            throw $th;
        }
    }

    /**
     * @throws Throwable
     */
    public function crearDiagnosticosFicha(Model $entidad, array|null $data)
    {
        try {
            DB::beginTransaction();
            if (!is_null($data))
                foreach ($data as $d) {
                    $entidad->diagnosticos()->create([
                        'diagnostico_id' => $d['diagnostico_id'],
                        'tipo' => $d['tipo'],
                    ]);
                }
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error en crearDiagnosticosFicha']);
            throw $th;
        }
    }

    /**
     * @throws Throwable
     */
    public function crearAptitudMedica(Model $entidad, array $data)
    {
        try {
            DB::beginTransaction();
            $entidad->aptitudesMedicas()->create([
                'tipo_aptitud_id' => $data['tipo_aptitud_id'],
                'observacion' => $data['observacion'],
                'limitacion' => $data['limitacion'],
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error en crearDiagnosticosFicha']);
            throw $th;
        }
    }
}
