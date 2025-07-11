<?php

namespace Src\App\Medico;

use App\Models\Medico\AccidenteEnfermedadLaboral;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class PolymorphicMedicoModelsService
{


    /**
     * Aqui se sincronizan todos los datos relacionados a una ficha.
     * Tanto para insertar como para actualizar registros existentes enlazados a la ficha.
     * @param Model $entidad La ficha preocupacional, periodica, etc.
     * @param $request
     * @throws Throwable
     */
    public function syncronizarInformacionFicha(Model $entidad, $request)
    {
        $this->crearAntecedenteClinico($entidad, $request->antecedente_clinico_quirurgico);
        $this->crearHabitosToxicos($entidad, $request->habitos_toxicos);
        $this->crearActividadesFisicas($entidad, $request->actividades_fisicas);
        $this->crearMedicaciones($entidad, $request->medicaciones);
        if ($request->tiene_accidente_trabajo)
            $this->crearAccidentesEnfermedadesProfesionales($entidad, $request->accidente_trabajo, AccidenteEnfermedadLaboral::ACCIDENTE_TRABAJO);
        if ($request->tiene_enfermedad_profesional)
            $this->crearAccidentesEnfermedadesProfesionales($entidad, $request->enfermedad_profesional, AccidenteEnfermedadLaboral::ENFERMEDAD_PROFESIONAL);
        $this->crearAntecedentesFamiliares($entidad, $request->antecedentes_familiares);
        $this->crearFactoresRiesgoPuestoTrabajoActual($entidad, $request->factoresRiesgoPuestoActual);
        $this->crearRevisionesActualesOrganosSistemas($entidad, $request->revisiones_actuales_organos_sistemas);
        $this->crearConstanteVital($entidad, $request->constante_vital);
        $this->crearExamenesFisicosRegionales($entidad, $request->examenes_fisicos_regionales);
        $this->crearDiagnosticosFicha($entidad, $request->diagnosticos);
        $this->crearAptitudMedica($entidad, $request->aptitud_medica);
    }

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
                    $habito = $entidad->habitosToxicos()->where('tipo_habito_toxico_id', $d['tipo_habito_toxico_id'])->first();
                    if ($habito) {
                        $habito->update([
                            'tiempo_consumo_meses' => $d['tiempo_consumo_meses'],
                            'cantidad' => $d['cantidad'],
                            'ex_consumidor' => $d['ex_consumidor'],
                            'tiempo_abstinencia_meses' => $d['tiempo_abstinencia_meses'],
                        ]);
                    } else
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
        if (empty($data)) return;

        try {
            DB::beginTransaction();

            $nombresEnviados = [];

            foreach ($data as $d) {
                $nombre = $d['nombre_actividad'];
                $nombresEnviados[] = $nombre;

                $entidad->actividadesFisicas()->updateOrCreate([
                    'nombre_actividad' => $nombre], [
                    'tiempo' => $d['tiempo']]);
            }

            //Eliminar las actividades que ya no están
            $entidad->actividadesFisicas()
                ->whereNotIn('nombre_actividad', $nombresEnviados)
                ->delete();

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
        if (empty($data)) {
            return; // Nada que registrar
        }

        try {
            DB::beginTransaction();

            $nombresEnviados = [];

            foreach ($data as $d) {
                $nombre = $d['nombre'];
                $nombresEnviados[] = $nombre;

                $entidad->medicaciones()->updateOrCreate([
                    'nombre' => $nombre], [
                    'cantidad' => $d['cantidad']
                ]);
            }

            //Eliminar medicaciones que ya no están
            $entidad->medicaciones()
                ->whereNotIn('nombre', $nombresEnviados)
                ->delete();

            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error en crearMedicacion', $th->getMessage()]);
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
        if (is_null($data)) return;

        try {
            DB::beginTransaction();
            $entidad->accidentesEnfermedades()->updateOrCreate([
                'tipo' => $tipo],
                [
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
    public function crearAntecedentesFamiliares(Model $entidad, array|null $data)
    {
        if (is_null($data)) return;

        try {
            DB::beginTransaction();

            $clavesRecibidas = [];

            foreach ($data as $d) {
                $clave = $d['tipo_antecedente_familiar_id'] . '-' . $d['parentesco'];
                $clavesRecibidas[] = $clave;

                $entidad->antecedentesFamiliares()->updateOrCreate([
                    'tipo_antecedente_familiar_id' => $d['tipo_antecedente_familiar_id'],
                    'parentesco' => $d['parentesco'],
                ], [
                    'descripcion' => $d['descripcion'],
                ]);
            }

            // Eliminar antecedentes no presentes en el nuevo array
            $entidad->antecedentesFamiliares()
                ->whereNotIn(DB::raw("CONCAT(tipo_antecedente_familiar_id, '-', parentesco)"), $clavesRecibidas)
                ->delete();

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
        if (is_null($data)) return;
        try {
            DB::beginTransaction();

            // Eliminamos factores de riesgo previos y datos relacionados
            $entidad->frPuestoTrabajoActual()->each(function ($item) {
                $item->detalleCategFactorRiesgoFrPuestoTrabAct()->delete();
                $item->delete();
            });

            // Se crea los nuevos
            foreach ($data as $d) {
                $factor = $entidad->frPuestoTrabajoActual()->create([
                    'puesto_trabajo' => $d['puesto_trabajo'],
                    'actividad' => $d['actividad'],
                    'tiempo_trabajo' => $d['tiempo_trabajo'] ?? null,
                    'medidas_preventivas' => $d['medidas_preventivas'],
                ]);

                foreach ($d['categorias'] as $c) {
                    $factor->detalleCategFactorRiesgoFrPuestoTrabAct()->create([
                        'categoria_factor_riesgo_id' => $c
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

            $organosEntrantes = collect($data)->pluck('organo_id')->all();

            // 2. Obtener los IDs actuales asociados a la entidad
            $entidad->revisionesActualesOrganosSistemas()->pluck('organo_id')->all();

            // 3. Eliminar los que no vinieron desde el frontend
            $entidad->revisionesActualesOrganosSistemas()
                ->whereNotIn('organo_id', $organosEntrantes)
                ->delete();

            foreach ($data as $d) {
                $entidad->revisionesActualesOrganosSistemas()->updateOrCreate(
                    ['organo_id' => $d['organo_id']],
                    ['descripcion' => $d['descripcion']]
                );
            }
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error en crearFactoresRiesgoPuestoTrabajoActual', $th->getMessage()]);
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
            $entidad->constanteVital()->updateOrCreate([],
                [
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

            $idsRecibidos = [];

            if (!is_null($data))
                foreach ($data as $d) {
                    $registro  = $entidad->examenesFisicosRegionales()->updateOrCreate(
                        ['categoria_examen_fisico_id' => $d['categoria_examen_fisico_id']],
                        ['observacion' => $d['observacion']]
                    );
                    $idsRecibidos[] = $registro->id;
                }

            //Eliminar los que no son necesarios
            $entidad->examenesFisicosRegionales()->whereNotIn('id', $idsRecibidos)->delete();

            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error en crearExamenesFisicosRegionales', $th->getMessage()]);
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

            $idsRecibidos = [];

            if (!is_null($data))
                foreach ($data as $d) {
                    $registro = $entidad->diagnosticos()->updateOrCreate([
                        'diagnostico_id' => $d['diagnostico_id'],
                        'tipo' => $d['tipo'],
                    ],[]);

                    $idsRecibidos[] = $registro->id;
                }

            // Elimina  los diagnosticos que no vinieron
            $entidad->diagnosticos()->whereNotIn('id', $idsRecibidos)->delete();

            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error en crearDiagnosticosFicha', $th->getMessage()]);
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
            $entidad->aptitudesMedicas()->updateOrCreate([], [
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
