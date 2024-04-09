<?php

namespace Src\App\Medico;

use App\Models\Empleado;
use App\Models\MaterialEmpleado;
use App\Models\MaterialEmpleadoTarea;
use App\Models\Medico\ActividadPuestoTrabajo;
use App\Models\Medico\AntecedenteGinecoObstetrico;
use App\Models\Medico\AntecedentePersonal;
use App\Models\Medico\AntecedenteTrabajoAnterior;
use App\Models\Medico\ConstanteVital;
use App\Models\Medico\DescripcionAntecedenteTrabajo;
use App\Models\Medico\Diagnostico;
use App\Models\Medico\EstadoSolicitudExamen;
use App\Models\Medico\EstiloVida;
use App\Models\Medico\Examen;
use App\Models\Medico\ExamenEspecifico;
use App\Models\Medico\ExamenPreocupacional;
use App\Models\Medico\HabitoToxico;
use App\Models\Medico\Medicacion;
use App\Models\Medico\FichaPreocupacional;
use App\Models\Proyecto;
use App\Models\Subtarea;
use App\Models\Tarea;
use Illuminate\Support\Facades\Log;

class FichaPreocupacionalService
{
    private $preocupacional_id;
    private $preocupacional;
    private $antecedente_personal;
    public function __construct($preocupacional_id)
    {
        $this->preocupacional_id = $preocupacional_id;
        $this->preocupacional = FichaPreocupacional::find($preocupacional_id);
    }

    public function agregarHabitosToxicos(array $habitos_toxicos)
    {
        foreach ($habitos_toxicos as $key => $value) {
            HabitoToxico::create(array(
                'tipo_habito_toxico_id' => $value->tipo_habito_toxico,
                'tiempo_consumo' => $value->$value->tiempo_consumo,
                'preocupacional_id' => $this->preocupacional_id
            ));
        }
    }
    public function agregarEstiloVida(array $estilos_vida)
    {
        foreach ($estilos_vida as $key => $value) {
            EstiloVida::create(array(
                'nombre_actividad' => $value->nombre_actividad,
                'tiempo' => $value->$value->tiempo,
                'preocupacional_id' => $this->preocupacional_id
            ));
        }
    }
    public function agregarMedicacion(array $medicaciones)
    {
        foreach ($medicaciones as $key => $value) {
            Medicacion::create(array(
                [
                    'nombre' => $value->nombre,
                    'cantidad' => $value->cantidad,
                    'preocupacional_id' => $this->preocupacional_id
                ]
            ));
        }
    }
    public function agregarActividadPuestoTrabajo(array $actividades_puestos_trabajos)
    {
        foreach ($actividades_puestos_trabajos as $key => $value) {
            ActividadPuestoTrabajo::create(array([
                'actividad' => $value->actividad,
                'preocupacional_id' => $this->preocupacional_id
            ]));
        }
    }
    public function agregarExamenesExpecificos(array $examenes_especificos)
    {
        foreach ($examenes_especificos as $key => $value) {
            ExamenEspecifico::create(array([
                'examen' => $value->examen,
                'fecha' => $value->fecha,
                'resultados' => $value->resultados,
                'preocupacional_id' => $this->preocupacional_id
            ]));
        }
    }
    public function agregarDiagnosticos(array $diagnosticos)
    {
        foreach ($diagnosticos as $key => $value) {
            Diagnostico::create(array([
                'examen' => $value->examen,
                'fecha' => $value->fecha,
                'resultados'    => $value->resultados,
                'preocupacional_id' => $this->preocupacional_id
            ]));
        }
    }
    public function agregarAntecedentesEmpleosAnteriores(array $antecedentes_empleos_anteriores)
    {
        foreach ($antecedentes_empleos_anteriores as $key => $value) {
            AntecedenteTrabajoAnterior::create(array(
                [
                    'empresa' => $value->empresa,
                    'puesto_trabajo' => $value->puesto_trabajo,
                    'actividades_desempenaba'   => $value->actividades_desempen,
                    'tiempo_tabajo' => $value->tiempo_tabajo,
                    'r_fisico' => $value->r_fisico,
                    'r_mecanico' => $value->r_mecanico,
                    'r_quimico' => $value->r_quimico,
                    'r_biologico' => $value->r_biologico,
                    'r_ergonomico' => $value->r_ergonomico,
                    'r_phisosocial' => $value->r_phisosocial,
                    'observacion' => $value->observacion,
                    'preocupacional_id' => $this->preocupacional_id
                ]
            ));
        }
    }

    public function insertarAntecedentesPersonales(AntecedentePersonal $antecedente_personal)
    {
        $antecedente_personal->preocupacional_id =  $this->preocupacional_id;
        $this->antecedente_personal = $antecedente_personal->save();
    }
    public function agregarExamenes(array $examenes)
    {
        foreach ($examenes as $key => $value) {
            ExamenPreocupacional::create(array(
                'nombre' => $value->nombre,
                'tiempo' => $value->tiempo,
                'resultados' => $value->resultados,
                'genero' => $value->genero,
                'antecedente_personal_id' =>   $this->antecedente_personal?->id
            ));
        }
    }
    public function insertarAntecedentesGinecoObstetricos(AntecedenteGinecoObstetrico $antecedente_gineco_obstetrico)
    {
        $antecedente_gineco_obstetrico->preocupacional_id =  $this->antecedente_personal?->id;
        $antecedente_gineco_obstetrico->save();
    }
    public function insertarDescripcionAntecedenteTrabajo(DescripcionAntecedenteTrabajo $descripcion_antecedente_trabajo)
    {
        $descripcion_antecedente_trabajo->preocupacional_id =  $this->preocupacional_id;
        $descripcion_antecedente_trabajo->save();
    }
    public function insertarConstanteVital(ConstanteVital $constante_vital)
    {
        $constante_vital->preocupacional_id =  $this->preocupacional_id;
        $constante_vital->save();
    }
}
