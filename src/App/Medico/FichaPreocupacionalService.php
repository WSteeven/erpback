<?php

namespace Src\App\Medico;

use App\Models\Medico\ActividadFisica;
use App\Models\Medico\ActividadPuestoTrabajo;
use App\Models\Medico\AntecedenteGinecoObstetrico;
use App\Models\Medico\AntecedentePersonal;
use App\Models\Medico\AntecedenteTrabajoAnterior;
use App\Models\Medico\ConstanteVital;
use App\Models\Medico\DescripcionAntecedenteTrabajo;
use App\Models\Medico\Medicacion;
use App\Models\Medico\FichaPreocupacional;
use App\Models\Medico\ResultadoExamenPreocupacional;
use App\Models\Medico\ResultadoHabitoToxico;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class FichaPreocupacionalService
{
    private $ficha_preocupacional_id;
    private $preocupacional;
    private $antecedente_personal;
    public function __construct($ficha_preocupacional_id)
    {
        $this->ficha_preocupacional_id = $ficha_preocupacional_id;
        $this->preocupacional = FichaPreocupacional::find($ficha_preocupacional_id);
    }

    public function agregarHabitosToxicos(array $habitos_toxicos)
    {
        try {
            foreach ($habitos_toxicos as $key => $value) {
                DB::beginTransaction();
                ResultadoHabitoToxico::create(
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
            throw ValidationException::withMessages([
                'Error al insertar registro habitos toxicos' => [$e->getMessage(), $e->getLine()],
            ]);
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
            throw ValidationException::withMessages([
                'Error al insertar registro actividades fisicas' => [$e->getMessage(), $e->getLine()],
            ]);
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
            throw ValidationException::withMessages([
                'Error al insertar registro medicaciones' => [$e->getMessage(), $e->getLine()],
            ]);
        }
    }
    public function agregarActividadesPuestoTrabajo(array $actividades_puestos_trabajos)
    {
        try {
            foreach ($actividades_puestos_trabajos as $key => $value) {
                DB::beginTransaction();
                ActividadPuestoTrabajo::create(
                    [
                        'actividad' => $value['actividad'],
                        'ficha_preocupacional_id' => $this->ficha_preocupacional_id
                    ]
                );
                DB::commit();
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro actividad de puesto de trabajos' => [$e->getMessage(), $e->getLine()],
            ]);
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
            throw ValidationException::withMessages([
                'Error al insertar registro antecedentes de empleos anteriores' => [$e->getMessage(), $e->getLine()],
            ]);
        }
    }
    public function insertarAntecedentesPersonales(AntecedentePersonal $antecedente_personal)
    {
        try {
            DB::beginTransaction();
            $antecedente_personal->ficha_preocupacional_id =  $this->ficha_preocupacional_id;
            $antecedente_personal->save();
            $this->antecedente_personal =$antecedente_personal;
            DB::commit();
        }catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro antecedentes personales' => [$e->getMessage(), $e->getLine()],
            ]);
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
            throw ValidationException::withMessages([
                'Error al insertar registro examenes' => [$e->getMessage(), $e->getLine()],
            ]);
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
            throw ValidationException::withMessages([
                'Error al insertar registro antecedentes gineco obstetricos' => [$e->getMessage(), $e->getLine()],
            ]);
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
            throw ValidationException::withMessages([
                'Error al insertar registro descripcion antecedente de trabajo' => [$e->getMessage(), $e->getLine()],
            ]);
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
            throw ValidationException::withMessages([
                'Error al insertar registro constante vital' => [$e->getMessage(), $e->getLine()],
            ]);
        }
    }
}
