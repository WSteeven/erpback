<?php

namespace App\Http\Resources\Medico;

use App\Models\Medico\AccidenteEnfermedadLaboral;
use App\Models\Medico\ExamenOrganoReproductivo;
use App\Models\Medico\RevisionActualOrganoSistema;
use App\Models\Medico\SistemaOrganico;
use App\Models\Medico\TipoHabitoToxico;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\False_;
use Src\App\Medico\FichasMedicasService;

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
        $controller_method = $request->route()->getActionMethod();
        $modelo = [
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
            'actividades_fisicas' => $this->actividadesFisicas,
            'medicaciones' => $this->medicaciones,
            'enfermedad_actual' => $this->enfermedad_actual,
            'recomendaciones_tratamiento' => $this->recomendaciones_tratamiento,
            'grupo_sanguineo' => $this->grupo_sanguineo==""?null:$this->grupo_sanguineo,
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
            'antecedente_personal' => new AntecedentePersonalResource($this->antecedentePersonal),
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
            'habitos_toxicos' => $this->mapearHabitosToxicos(),

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
            /*'antecedentes_empleos_anteriores' => $this->antecedentesTrabajosAnteriores()->with(['riesgos' => function ($query) {
                $query->pluck('tipo_riesgo_id');
            }])->get(),*/

            'antecedentes_empleos_anteriores' => $this->mapearAntecedentesEmpleosAnteriores(),
            /*****************************************
             * Fin Antecedentes de Trabajos Anteriores
             * ***************************************/
            /*****************************************
             * Descripcion de Antecedentes de Trabajo
             * ***************************************/
            'accidente_trabajo' => $this->mapearAccidenteTrabajo($this->accidentesEnfermedades()->where('tipo', AccidenteEnfermedadLaboral::ACCIDENTE_TRABAJO)->orderBy('id', 'desc')->first()),
            'enfermedad_profesional' => $this->mapearAccidenteTrabajo($this->accidentesEnfermedades()->where('tipo', AccidenteEnfermedadLaboral::ENFERMEDAD_PROFESIONAL)->orderBy('id', 'desc')->first()),
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
            'revisiones_actuales_organos_sistemas' => $this->mapearRevisionesActualesOrganosSistemas(), //)->map(fn($item) => [
            // 'descripcion' => $item,
            // 'organo_id' => $item->organo_id,
            // 'organo' => $item->organoSistema->nombre,
            // ]),
            /*********************************************
             * Fin Revision  actual de organos y sistemas
             * *******************************************/
            /*****************************************
             *Constantes vitales y antropometría
             * ***************************************/
            'constante_vital' => [
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
            ],
            /*********************************************
             * Fin Constantes vitales y antropometría
             *********************************************/
            /*****************************************
             *Examenes fisicos regionales
             * ***************************************/
            /*'examenes_fisicos_regionales' => $this->examenesFisicosRegionales()->get()->map(fn ($item) => [
                'categoria_examen_fisico_id' => $item->categoria_examen_fisico_id,
                'categoria_examen_fisico' => $item->categoriaexamenFisico->nombre,
                'observacion' => $item['observacion'],
            ]),*/
            'examenes_fisicos_regionales' => FichasMedicasService::mapearExamenesFisicosRegionales($this),
            /*********************************************
             * Fin Examenes fisicos regionales
             * *******************************************/
            /*****************************************
             *Aptitud medica de trabajo
             * ***************************************/
            'aptitud_medica' => $this->aptitudesMedicas,
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
            'fr_puestos_trabajos_actuales' => FrPuestoTrabajoActualResource::collection($this->frPuestoTrabajoActual),
            'cargo' => $this->cargo_id,
            'lateralidad' => $this->lateralidad,
            'actividades_extralaborales' => $this->actividades_extralaborales,
            'profesional_salud_id' => $this->profesional_salud_id,
            // 'antecedente_personal' => $this->antecedentePersonal()->first(),
            'examenes_realizados' => $this->mapearExamenesRealizados(),
            'antecedentes_gineco_obstetricos' => $this->antecedentePersonal->antecedenteGinecoobstetrico,
        ];

        if ($controller_method == 'show') {
//            $modelo['habitos_toxicos'] =ResultadoHabitoToxicoResource::collection($this->habitosToxicos);
            $modelo['habitos_toxicos'] =$this->mapearHabitosToxicos();
        }

            return  $modelo;
    }

    private function mapearRevisionesActualesOrganosSistemas()
    {
        return $this->revisionesActualesOrganosSistemas()->get()->map(fn($revision) =>
            [
                'descripcion' => $revision->descripcion,
                'organo_id' => $revision->organo_id,
                'organo' => SistemaOrganico::find($revision->organo_id)->nombre,
            ]
        );
        // if (!$revision) return null;
        /*return [
            'descripcion' => $revision->descripcion,
            'organo_id' => $revision->organo_id,
            'organo' => SistemaOrganico::find($revision->organo_id)->nombre,
        ];*/
    }

    private function mapearExamenesRealizados()
    {
        $examenes = $this->examenesRealizados()->get();
        // if (!$examenes) return null;
        return $examenes->map(fn ($examen_realizado) => [
            'tiempo' => $examen_realizado->tiempo,
            'resultado' => $examen_realizado->resultado,
            'examen_id' => $examen_realizado->examen_id,
            'examen' => ExamenOrganoReproductivo::find($examen_realizado->examen_id)->examen,
            'tipo' => ExamenOrganoReproductivo::find($examen_realizado->examen_id)->tipo,
            // 'organo' => SistemaOrganico::find($revision->organo_id)->nombre,
        ]);
    }

    private function mapearAccidenteTrabajo(AccidenteEnfermedadLaboral|null $accidente_enfermedad_laboral)
    {
        if (!$accidente_enfermedad_laboral) return null;
        return [
            'id' => $accidente_enfermedad_laboral->id,
            'calificado_iss' => boolval($accidente_enfermedad_laboral?->calificado_iss),
            'instituto_seguridad_social' => $accidente_enfermedad_laboral?->instituto_seguridad_social,
            'fecha' => $accidente_enfermedad_laboral?->fecha ? Carbon::parse($accidente_enfermedad_laboral?->fecha)?->format('Y-m-d') : null,
            'observacion' => $accidente_enfermedad_laboral?->observacion,
            'tipo_descripcion_antecedente_trabajo' => $accidente_enfermedad_laboral?->tipo,
            'ficha_preocupacional_id' => $accidente_enfermedad_laboral?->ficha_preocupacional_id,
        ];
    }

    private function mapearHabitosToxicos()
    {
        $tiposHabitos = TipoHabitoToxico::get(['id', 'nombre']);
        $habitosToxicos = $this->habitosToxicos;

        return $tiposHabitos->map(function ($tipo_habito) use ($habitosToxicos) {
            $habito = $habitosToxicos->first(fn ($h) => $h->tipo_habito_toxico_id == $tipo_habito->id);

            return [
                'tipo_habito_toxico' => $habito ? $habito->tipoHabitoToxico->nombre : $tipo_habito->nombre,
                'tipo_habito_toxico_id' => $habito ? $habito['tipo_habito_toxico_id'] : $tipo_habito->id,
                'tiempo_consumo_meses' => $habito ? $habito['tiempo_consumo_meses'] : '',
                'cantidad' => $habito ? $habito['cantidad'] : '',
                'ex_consumidor' => $habito ? $habito['ex_consumidor'] : false,
                'tiempo_abstinencia_meses' => $habito ? $habito['tiempo_abstinencia_meses'] : '',
                'consume' => $habito && !!$habito['tiempo_consumo_meses'],
//                'aplica' => $habito ? !!$habito['tiempo_consumo_meses'] : '',
            ];
        });

        /*return $this->habitosToxicos->map(fn ($habito) => [
            'tipo_habito_toxico' => $habito->tipoHabitoToxico->nombre,
            'tipo_habito_toxico_id' => $habito['tipo_habito_toxico_id'],
            'tiempo_consumo_meses' => $habito['tiempo_consumo_meses'],
            'cantidad' => $habito['cantidad'],
            'ex_consumidor' => $habito['ex_consumidor'],
            'tiempo_abstinencia_meses' => $habito['tiempo_abstinencia_meses'],
            'aplica' => $habito['tiempo_consumo_meses'] || $habito['tiempo_abstinencia_meses'] || $habito['cantidad'],
        ]);*/
    }

    private function mapearAntecedentesEmpleosAnteriores()
    {
        return $this->antecedentesTrabajosAnteriores->map(function ($antecedente) {
            $antecedente->observaciones = $antecedente->observacion;
            $antecedente->tipos_riesgos_ids = $antecedente->riesgos->pluck('tipo_riesgo_id')->toArray();
            return $antecedente;
        });
    }
}
