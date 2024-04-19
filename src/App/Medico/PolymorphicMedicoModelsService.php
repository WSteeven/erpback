<?php

namespace Src\App\Medico;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PolymorphicMedicoModelsService
{

    /**
     * Esta función crea antecedentes clínicos, uno a la vez.
     */
    public function crearAntecedenteClinico(Model $entidad, string|array $data)
    {
        try {
            DB::beginTransaction();
            if (is_array($data))
                foreach ($data as $d) {
                    $entidad->antecedentesClinicos()->create(['descripcion' => $d]);
                }
            else
                $entidad->antecedentesClinicos()->create(['descripcion' => $data]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error en crearAntecedenteClinico']);
            throw $th;
        }
    }

    public function crearHabitosToxicos(Model $entidad, array|null $data)
    {
        try {
            DB::beginTransaction();
            if (!is_null($data))
                foreach ($data as $d) {
                    $entidad->habitosToxicos()->create(
                        [
                            'tipo_habito_toxico_id' => $d['tipo_habito_toxico'],
                            'tiempo_consumo_meses' => $d['tiempo_consumo_meses'],
                            'cantidad' => $d['cantidad'],
                            'ex_consumidor' => $d['ex_consumidor'],
                            'tiempo_abstinencia_meses' => $d['tiempo_abstinencia_meses'],
                        ]
                    );
                }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error en crearHabitoToxico']);
            throw $th;
        }
    }

    /**
     * Está función recibe un objeto o un array para guardar una o varias actividades fisicas
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
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error en crearActividadFisica']);
            throw $th;
        }
    }

    /**
     * Está función recibe un objeto o un array para guardar una o varias medicaciones
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
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error en crearMedicacion']);
            throw $th;
        }
    }

    /**
     * Está función recibe un objeto o un array para guardar una o varias accidentes o enfermedades profesionales
     * Depende del tipo que reciba en cada objeto de `$data` se guardará como accidente de trabajo o como enfermedad profesional.
     */
    public function crearAccidentesEnfermedadesProfesionales(Model $entidad, array|null $data)
    {
        try {
            DB::beginTransaction();
            if (!is_null($data))
                foreach ($data as $d) {
                    $entidad->accidentesEnfermedades()->create([
                        'nombre' => $d['nombre'],
                        'cantidad' => $d['cantidad']
                    ]);
                }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error en crearMedicacion']);
            throw $th;
        }
    }

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
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error en crearAntecedentesFamiliares']);
            throw $th;
        }
    }

    public function crearFactoresRiesgoPuestoTrabajoActual(Model $entidad, array|null $data)
    {
        try {
            DB::beginTransaction();
            if (!is_null($data))
            foreach ($data as $d) {
                $entidad->antecedentesFamiliares()->create([
                    'puesto_trabajo' => $d['puesto_trabajo'],
                    'actividad' => $d['actividad'],
                    'tiempo_trabajo' => $d['tiempo_trabajo'],
                    'medidas_preventivas' => $d['medidas_preventivas'],
                ]);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error en crearFactoresRiesgoPuestoTrabajoActual']);
            throw $th;
        }
    }

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
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error en crearFactoresRiesgoPuestoTrabajoActual']);
            throw $th;
        }
    }

    public function crearExamenesFisicosRegionales(Model $entidad, array $data)
    {
        try {
            DB::beginTransaction();
            foreach ($data as $d) {
                $entidad->examenesFisicosRegionales()->create([
                    'categoria_examen_fisico_id' => $d['categoria_examen_fisico_id'],
                    'observacion' => $d['observacion'],
                ]);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error en crearFactoresRiesgoPuestoTrabajoActual']);
            throw $th;
        }
    }

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
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error en crearDiagnosticosFicha']);
            throw $th;
        }
    }

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
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error en crearDiagnosticosFicha']);
            throw $th;
        }
    }
}
