<?php

namespace App\Http\Resources\Medico;

use App\Models\Medico\AccidenteEnfermedadLaboral;
use App\Models\Medico\SistemaOrganico;
use App\Models\Medico\TipoHabitoToxico;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\App\Medico\FichasMedicasService;

class FichaPeriodicaResource extends JsonResource
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
            // 'ciu' => $this->ciu,
            'establecimiento_salud' => $this->establecimiento_salud,
            'numero_historia_clinica' => $this->numero_historia_clinica,
            'numero_archivo' => $this->numero_archivo,
            'puesto_trabajo' => $this->puesto_trabajo,
            'motivo_consulta' => $this->motivo_consulta,
            'incidentes' => $this->incidentes,
            'antecedentes_clinicos_quirurgicos' => $this->antecedentes_clinicos_quirurgicos,
            'registro_empleado_examen_id' => $this->registro_empleado_examen_id,
            'enfermedad_actual' => $this->enfermedad_actual,
            'observacion_examen_fisico_regional' => $this->observacion_examen_fisico_regional,
            'aptitud_medica' => $this->aptitudesMedicas,
            'cargo' => $this->cargo_id,
            //
            'actividades_fisicas' => $this->actividadesFisicas,
            'medicaciones' => $this->medicaciones,
            'habitos_toxicos' => $this->mapearHabitosToxicos(),
            'accidente_trabajo' => $this->mapearAccidenteTrabajo($this->accidentesEnfermedades()->where('tipo', AccidenteEnfermedadLaboral::ACCIDENTE_TRABAJO)->orderBy('id', 'desc')->first()),
            'enfermedad_profesional' => $this->mapearAccidenteTrabajo($this->accidentesEnfermedades()->where('tipo', AccidenteEnfermedadLaboral::ENFERMEDAD_PROFESIONAL)->orderBy('id', 'desc')->first()),

            'antecedentes_familiares' => $this->antecedentesFamiliares,

            'factores_riesgo' => $this->frPuestoTrabajoActual,
            'revisiones_actuales_organos_sistemas' => $this->mapearRevisionesActualesOrganosSistemas(),

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

            /* 'examenes_fisicos_regionales' => $this->examenesFisicosRegionales()->get()->map(fn ($item) => [
                'categoria_examen_fisico_id' => $item->categoria_examen_fisico_id,
                'categoria_examen_fisico' => $item->categoriaexamenFisico->nombre,
                'observacion' => $item['observacion'],
            ]),*/
            'examenes_fisicos_regionales' => FichasMedicasService::mapearExamenesFisicosRegionales($this),
        ];

        // if ($controller_method == 'show') {
        /*$modelo['habitos_toxicos'] = $this->mapearHabitosToxicos();
            $modelo['accidente_trabajo'] = $this->mapearAccidenteTrabajo($this->accidentesEnfermedades()->where('tipo', AccidenteEnfermedadLaboral::ACCIDENTE_TRABAJO)->orderBy('id', 'desc')->first());
            $modelo['enfermedad_profesional'] = $this->mapearAccidenteTrabajo($this->accidentesEnfermedades()->where('tipo', AccidenteEnfermedadLaboral::ENFERMEDAD_PROFESIONAL)->orderBy('id', 'desc')->first());
            
            $modelo['antecedentes_familiares'] = $this->antecedentesFamiliares;
            
            $modelo['factores_riesgo'] = $this->frPuestoTrabajoActual;
            $modelo['revisiones_actuales_organos_sistemas'] = $this->mapearRevisionesActualesOrganosSistemas();
            
            $modelo['constante_vital'] = [
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
            ];
            
            $modelo['examenes_fisicos_regionales'] = $this->examenesFisicosRegionales()->get()->map(fn ($item) => [
                'categoria_examen_fisico_id' => $item->categoria_examen_fisico_id,
                'categoria_examen_fisico' => $item->categoriaexamenFisico->nombre,
                'observacion' => $item['observacion'],
            ]);*/
        // $modelo['antecedente_clinico_quirurgico'] = $this->antecedentesClinicos()->orderBy('id', 'desc')->first()->descripcion;
        // $modelo['accidente_trabajo'] = $this->accidentesEnfermedades()->where('tipo', AccidenteEnfermedadLaboral::ACCIDENTE_TRABAJO)->orderBy('id', 'desc')->first();
        // $modelo['enfermedadesProfesionales'] = $this->accidentesEnfermedades()->where('tipo', AccidenteEnfermedadLaboral::ENFERMEDAD_PROFESIONAL)->orderBy('id', 'desc')->first();
        // $modelo['factoresRiesgoPuestoActual'] = $this->frPuestoTrabajoActual;
        // $modelo['examenesFisicosRegionales'] = $this->examenesFisicosRegionales;
        // $modelo['revisionesOrganosSistemas'] = $this->revisionesActualesOrganosSistemas;
        // $modelo['constanteVital'] = $this->constanteVital()->first();
        // $modelo['diagnosticos'] = $this->diagnosticos;
        // $modelo['aptitudMedica'] = $this->aptitudesMedicas()->first();
        // }

        return $modelo;
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
                'aplica' => $habito ? !!$habito['tiempo_consumo_meses'] : '',
            ];
        });
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

    private function mapearRevisionesActualesOrganosSistemas()
    {
        return $this->revisionesActualesOrganosSistemas()->get()->map(
            fn ($revision) =>
            [
                'descripcion' => $revision->descripcion,
                'organo_id' => $revision->organo_id,
                'organo' => SistemaOrganico::find($revision->organo_id)->nombre,
            ]
        );
    }
}
