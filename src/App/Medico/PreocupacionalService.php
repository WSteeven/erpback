<?php

namespace Src\App\Medico;

use App\Models\Empleado;
use App\Models\MaterialEmpleado;
use App\Models\MaterialEmpleadoTarea;
use App\Models\Medico\AntecedenteGinecoObstetrico;
use App\Models\Medico\AntecedentePersonal;
use App\Models\Medico\ConstanteVital;
use App\Models\Medico\DescripcionAntecedenteTrabajo;
use App\Models\Medico\EstadoSolicitudExamen;
use App\Models\Medico\Examen;
use App\Models\Medico\Preocupacional;
use App\Models\Proyecto;
use App\Models\Subtarea;
use App\Models\Tarea;
use Illuminate\Support\Facades\Log;

class PreocupacionalService
{
    private $preocupacional_id;
    private $preocupacional;
    private $antecedente_personal;
    public function __construct($preocupacional_id)
    {
        $this->preocupacional_id = $preocupacional_id;
        $this->preocupacional = Preocupacional::find($preocupacional_id);
    }
    public function insertarAntecedentesPersonales(AntecedentePersonal $antecedente_personal)
    {
        $antecedente_personal->preocupacional_id =  $this->preocupacional_id;
        $this->antecedente_personal = $antecedente_personal->save();
        /* $antecedente_personal = AntecedentePersonal::create([
            'antecedentes_quirorgicos' => $antecedente_personal->antecedentes_quirorgicos,
            'vida_sexual_activa' => $antecedente_personal->vida_sexual_activa,
            'tiene_metodo_planificacion_familiar' => $antecedente_personal->tiene_metodo_planificacion_familiar,
            'tipo_metodo_planificacion_familiar' => $antecedente_personal->tipo_metodo_planificacion_familiar,
            'preocupacional_id' => $this->preocupacional_id,
        ]);*/
        $this->antecedente_personal = $antecedente_personal;
    }
    public function insertarAntecedentesGinecoObstetricos(AntecedenteGinecoObstetrico $antecedente_gineco_obstetrico)
    {
        $antecedente_gineco_obstetrico->preocupacional_id =  $this->antecedente_personal?->id;
        $antecedente_gineco_obstetrico->save();
        /*AntecedenteGinecoObstetrico::create([
            'menarquia' => $antecedente_gineco_obstetrico->menarquia,
            'ciclos' => $antecedente_gineco_obstetrico->ciclos,
            'fecha_ultima_menstruacion' => $antecedente_gineco_obstetrico->fecha_ultima_menstruacion,
            'gestas' => $antecedente_gineco_obstetrico->gestas,
            'partos' => $antecedente_gineco_obstetrico->partos,
            'cesareas' => $antecedente_gineco_obstetrico->cesareas,
            'abortos' => $antecedente_gineco_obstetrico->abortos,
            'hijos_vivos' => $antecedente_gineco_obstetrico->hijos_vivos,
            'hijos_muertos' => $antecedente_gineco_obstetrico->hijos_muertos,
            'antecedentes_personales_id' => $this->antecedente_personal->id,
        ]);*/
    }
    public function insertarDescripcionAntecedenteTrabajo(DescripcionAntecedenteTrabajo $descripcion_antecedente_trabajo)
    {
        $descripcion_antecedente_trabajo->preocupacional_id =  $this->preocupacional_id;
        $descripcion_antecedente_trabajo->save();
        /*DescripcionAntecedenteTrabajo::create([
            'calificado_iess' => $descripcion_antecedente_trabajo->calificado_iess,
            'descripcion' => $descripcion_antecedente_trabajo->descripcion,
            'fecha' => $descripcion_antecedente_trabajo->fecha,
            'observacion' => $descripcion_antecedente_trabajo->observacion,
            'tipo_descripcion_antecedente_trabajo' => $descripcion_antecedente_trabajo->tipo_descripcion_antecedente_trabajo,
            'preocupacional_id' => $this->preocupacional_id,
        ]);*/
    }
    public function insertarConstanteVital(ConstanteVital $constante_vital)
    {
        $constante_vital->preocupacional_id =  $this->preocupacional_id;
        $constante_vital->save();
       /* ConstanteVital::create([
            'presion_arterial' => $constante_vital->presion_arterial,
            'temperatura' => $constante_vital->temperatura,
            'frecuencia_cardiaca' => $constante_vital->frecuencia_cardiaca,
            'saturacion_oxigeno' => $constante_vital->saturacion_oxigeno,
            'frecuencia_respiratoria' => $constante_vital->frecuencia_respiratoria,
            'peso' => $constante_vital->peso,
            'estatura' => $constante_vital->estatura,
            'talla' => $constante_vital->talla,
            'indice_masa_corporal' => $constante_vital->indice_masa_corporal,
            'perimetro_abdominal' => $constante_vital->perimetro_abdominal,
            'preocupacional_id' => $this->preocupacional_id,

        ]);*/
    }
}
