<?php

namespace App\Http\Resources\Medico;

use App\Models\Medico\AccidenteEnfermedadLaboral;
use Illuminate\Http\Resources\Json\JsonResource;

class FichaPreocupacionalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'ciu' => $this->ciu,
            'establecimiento_salud' => $this->establecimiento_salud,
            'numero_historia_clinica' => $this->numero_historia_clinica,
            'numero_archivo' => $this->numero_archivo,
            'puesto_trabajo' => $this->puesto_trabajo,
            'religion' => $this->religion_id,
            'religion_info' => $this->religion !== null ? $this->religion?->nombre : ' ',
            'orientacion_sexual' => $this->orientacion_sexual_id,
            'orientacion_sexual_info' => $this->orientacionSexual !== null ? $this->orientacionSexual?->nombre : ' ',
            'identidad_genero' => $this->identidad_genero_id,
            'identidad_genero_info' => $this->identidadGenero !== null ? $this->identidadGenero?->nombre : '',
            'actividades_relevantes_puesto_trabajo_ocupar' => $this->actividades_relevantes_puesto_trabajo_ocupar,
            'motivo_consulta' => $this->motivo_consulta,
            'empleado' => $this->empleado_id,
            'empleado_info' => $this->empleado !== null ? $this->empleado?->nombres . '' . $this->empleado?->apellidos : ' ',
            'actividad_fisica' => $this->actividad_fisica,
            'enfermedad_actual' => $this->enfermedad_actual,
            'recomendaciones_tratamiento' => $this->recomendaciones_tratamiento,
            'descripcion_examen_fisico_regional' => $this->descripcion_examen_fisico_regional,
            'descripcion_revision_organos_sistemas' => $this->descripcion_revision_organos_sistemas,
            /***************************
             * Antecedentes Personales
             * *************************/
            'antecedentes_quirurgicos' => $this->antecedentePersonal->antecedentes_quirurgicos,
            'vida_sexual_activa' => $this->antecedentePersonal->vida_sexual_activa,
            'tiene_metodo_planificacion_familiar' => $this->antecedentePersonal->tiene_metodo_planificacion_familiar,
            'tipo_metodo_planificacion_familiar' => $this->antecedentePersonal->tipo_metodo_planificacion_familiar,
            /*******************************
             * fin Antecedentes personales
             *******************************/
            /*********************************
             * Antecedentes Ginecoobstetricos
             * *******************************/
            'antecedentePersonal' => new AntecedentePersonalResource($this->antecedentePersonal),
            'menarquia' => $this->antecedentePersonal->antecedenteGinecoobstetrico?->menarquia,
            'ciclos' => $this->antecedentePersonal->antecedenteGinecoobstetrico?->ciclos,
            'fecha_ultima_menstruacion' => $this->antecedentePersonal->antecedenteGinecoobstetrico?->fecha_ultima_menstruacion,
            'gestas' => $this->antecedentePersonal->antecedenteGinecoobstetrico?->gestas,
            'partos' => $this->antecedentePersonal->antecedenteGinecoobstetrico?->partos,
            'cesareas' => $this->antecedentePersonal->antecedenteGinecoobstetrico?->cesareas,
            'abortos' => $this->antecedentePersonal->antecedenteGinecoobstetrico?->abortos,
            'abortos' => $this->antecedentePersonal->antecedenteGinecoobstetrico?->hijos_vivos,
            'abortos' => $this->antecedentePersonal->antecedenteGinecoobstetrico?->hijos_muertos,
            /**************************************
             * Fin Antecedentes Ginecoobstetricos
             **************************************/
            /****************************
             * Examenes Preocupacionales
             * **************************/
            'examenes_preocupacionales' => $this->antecedentePersonal->examenesPreocupacionales,

            /********************************
             * Fin Examenes Preocupacionales
             * ******************************/
            /****************************
             * Habito Toxico
             * **************************/
            'habitos_toxicos' => $this->habitosToxicos,

            /********************************
             * Fin Habito Toxico
             * ******************************/
            /****************************
             * Estilos de Vida
             * **************************/
            'estilos_vida' => $this->estilosVida,
            /********************************
             * Estilos de Vida
             * ******************************/
            /*************************************
             * Antecedentes de Trabajos Anteriores
             * ***********************************/
            'antecedentes_trabajos_anteriores' => $this->antecedentesTrabajosAnteriores,
            /*****************************************
             * Fin Antecedentes de Trabajos Anteriores
             * ***************************************/
            /*****************************************
             * Descripcion de Antecedentes de Trabajo
             * ***************************************/
            'accidentesTrabajo' => $this->accidentesEnfermedades()->where('tipo', AccidenteEnfermedadLaboral::ACCIDENTE_TRABAJO)->orderBy('id', 'desc')->first(),
            'enfermedadesProfesionales' => $this->accidentesEnfermedades()->where('tipo', AccidenteEnfermedadLaboral::ENFERMEDAD_PROFESIONAL)->orderBy('id', 'desc')->first(),
            /*********************************************
             * Fin Descripcion de Antecedentes de Trabajo
             * *******************************************/
            /*************************************
             * Antecedentes familiares
             * ***********************************/
            'antecedentes_familiares' => $this->antecedentesFamiliares,
            /*****************************************
             * Fin Antecedentes familiares
             * ***************************************/
            /*************************************
             * Actividades de Puesto de Trabajo
             * ***********************************/
            'actividades_puesto_trabajo' => $this->actividadesPuestoTrabajo,
            /*****************************************
             * Fin Actividades de Puesto de Trabajo
             * ***************************************/
            /***************************************
             *Factores de riesgo
             * ***********************************/
            'factores_riesgo' => $this->frPuestoTrabajoActual,
            /*****************************************
             * Fin Factores de riesgo
             * ***************************************/
            /*****************************************
             *Revision  actual de organos y sistemas
             * ***************************************/
            'revisiones_actuales_organos_sistemas' => $this->revisionesActualesOrganosSistemas()->first(),
            /*********************************************
             * Fin Revision  actual de organos y sistemas
             * *******************************************/
            /*****************************************
             *Constantes vitales y antropometría
             * ***************************************/
            'presion_arterial' => $this->constanteVital()->first()?->presion_arterial,
            'temperatura' => $this->constanteVital()->first()?->temperatura,
            'frecuencia_cardiaca' => $this->constanteVital()->first()?->frecuencia_cardiaca,
            'saturacion_oxigeno' => $this->constanteVital()->first()?->saturacion_oxigeno,
            'frecuencia_respiratoria' => $this->constanteVital()->first()?->frecuencia_respiratoria,
            'peso' => $this->constanteVital()->first()?->peso,
            'estatura' => $this->constanteVital()->first()?->estatura,
            'talla' => $this->constanteVital()->first()?->talla,
            'indice_masa_corporal' => $this->constanteVital()->first()?->indice_masa_corporal,
            'perimetro_abdominal' => $this->constanteVital()->first()?->perimetro_abdominal,
            /*********************************************
             * Fin Constantes vitales y antropometría
             *********************************************/
            /*****************************************
             *Examenes fisicos regionales
             * ***************************************/
            'examenes_fisicos_regionales' => $this->examenesFisicosRegionales()->first(),
            /*********************************************
             * Fin Examenes fisicos regionales
             * *******************************************/
            /*****************************************
             *Aptitud medica de trabajo
             * ***************************************/
            'aptitudes_medicas' => $this->aptitudesMedicas,
            /*********************************************
             * Aptitud medica de trabajo
             * *******************************************/
            /*****************************************
             *Antecedentes Familiares
             * ***************************************/
            'antecedentes_familiares' => $this->antecedentesFamiliares,
            /*****************************************
             *Antecedentes Familiares
             * ***************************************/
            'fr_puestos_trabajos_actuales' => FrPuestoTrabajoActualResource::collection($this->frPuestoTrabajoActual)
        ];
    }
}
